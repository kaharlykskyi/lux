<?php

namespace App\TecDoc;


use App\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPExcel_IOFactory;
use PhpImap\Mailbox;

class ImportPriceList
{

    protected $xls = null;

    protected $config = null;

    protected $stocks = [];

    protected $product_data = [];

    protected $count_success = 0;

    protected $count_fail = 0;

    protected $currency = null;

    public function __construct()
    {
        try{
            $client = new Client([
                'headers' => [
                    "cache-control: no-cache"
                ]
            ]);
            $this->currency = json_decode($client->get('https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11')->getBody());
        } catch (\Exception $e){
            if (config('app.debug')){
                dump("Can't connect to privatbank: $e");
            } else {
                Log::error("Can't connect to privatbank: $e");
            }
        }
        $this->getMail();
    }

    protected function getMail(){
        $price_list_configs = config('price_list_settings');

        try{
            $connect_to = '{imap.gmail.com:993/imap/ssl}INBOX';
            $user = config('app.work_mail');
            $password = config('app.work_pass');

            $mailbox = new Mailbox($connect_to,$user,$password, storage_path('app') . '/price_list');

            $mailsIds = $mailbox->searchMailbox('UNSEEN');

            if(!$mailsIds) return;

            foreach ($mailsIds as $mailsId){
                $mail = $mailbox->getMail($mailsId);
                foreach ($price_list_configs as $config){
                    $this->config = $config;
                    if($mail->fromAddress === $config['email']){

                        foreach ($mail->getAttachments() as $mailAttachment){
                            $this->export($mailAttachment->filePath);

                            DB::table('history_imports')->insert([
                                'company' => $this->config['company'],
                                'success' => $this->count_success,
                                'fail' => $this->count_fail,
                                'created_at' => Carbon::now()
                            ]);

                            unlink($mailAttachment->filePath);
                            $this->count_success = 0;
                            $this->count_fail = 0;
                        }

                        $mailbox->deleteMail($mailsId);
                    }
                }
                $mailbox->deleteMail($mailsId);
            }

        } catch (\Exception $exception){
            if (config('app.debug')){
                dump("Can't connect to '$connect_to': $exception");
            } else {
                Log::error("Can't connect to '$connect_to': $exception");
            }
        }
    }

    protected function export($file){
        if (file_exists($file)){
            try {
                $this->xls = PHPExcel_IOFactory::load($file);
                $this->xls->setActiveSheetIndex(0);
                $sheet = $this->xls->getActiveSheet();
                $rowIterator = $sheet->getRowIterator();
                foreach ($rowIterator as $row){
                    if ($row->getRowIndex() > $this->config['ignore_row_index']){
                        $cellIterator = $row->getCellIterator();
                        foreach ($cellIterator as $cell){
                            $cellPath = $cell->getColumn();
                            if (isset($this->config['stocks'])){
                                foreach ($this->config['stocks'] as $stock){
                                    if($stock['row'] === $row->getRowIndex() && $cellPath === $stock['column']){
                                        $this->stocks[$cellPath] = $this->stockQuery($cell->getCalculatedValue());
                                    }
                                }
                            }
                            if (isset($this->config['cells'][$cellPath]) && $row->getRowIndex() >= $this->config['data_row'] ){
                                $this->product_data[$row->getRowIndex()][$this->config['cells'][$cellPath]] = $cell->getCalculatedValue();
                            }
                            foreach ($this->stocks as $k => $stock){
                                if ($k === $cellPath && $row->getRowIndex() >= $this->config['data_row']){
                                    $this->product_data[$row->getRowIndex()]['count'][$cellPath] = $cell->getCalculatedValue();
                                }
                            }
                        }
                    }
                }

                $this->productQuery();

            } catch (\PHPExcel_Reader_Exception $e) {
                if (config('app.debug')){
                    dump("Error load '$file': $e");
                } else {
                    Log::error("Error load '$file': $e");
                }

                return false;
            } catch (\Exception $e){
                if (config('app.debug')){
                    dump("Error : $e");
                } else {
                    Log::error("Error : $e");
                }
            }
        }
    }

    protected function stockQuery($stockName){
        if(DB::table('stocks')->where([['name',$stockName],['company',$this->config['company']]])->exists()){
            return DB::table('stocks')->where([['name',$stockName],['company',$this->config['company']]])->first();
        }else {
            DB::table('stocks')->insert(['name' => $stockName,'company' => $this->config['company']]);
            return DB::table('stocks')->where([['name',$stockName],['company',$this->config['company']]])->first();
        }
    }

    protected function productQuery(){
        if (!empty($this->product_data)){
            foreach ($this->product_data as $k => $productInfo){
                if(isset($productInfo['currency']) && isset($this->currency)){
                    if ($productInfo['currency'] !== 'UAH'){
                        foreach ($this->currency as $item){
                            if ($productInfo['currency'] === $item->ccy){
                                $productInfo['price'] = (float)$productInfo['price'] * (float)$item->sale;
                                if (isset($productInfo['old_price'])){
                                    $productInfo['old_price'] = (float)$productInfo['old_price'] * (float)$item->sale;
                                }
                            }
                        }
                    }
                }
                try{
                    $array_import = [
                        'name' => $productInfo['name'],
                        'articles' => str_replace(' ','',$productInfo['articles']),
                        'brand' => $productInfo['brand'],
                        'alias' => str_replace(' ','_',$this->transliterateRU($productInfo['name'] .'_'. $productInfo['articles'] .'_'. $this->config['company'])),
                        'short_description' => isset($productInfo['short_description'])? $productInfo['short_description']: null,
                        'full_description' => isset($productInfo['full_description'])? $productInfo['full_description']: null,
                        'price' => round($productInfo['price'],2),
                        'company' => $this->config['company'],
                        'old_price' => isset($productInfo['old_price'])? round($productInfo['old_price'],2): null
                    ];

                    if(DB::table('products')->where([['articles',$productInfo['articles']],['company',$this->config['company']]])->exists()){
                        Product::where([['articles',$productInfo['articles']],['company',$this->config['company']]])
                            ->update($array_import);
                        $product = Product::where([['articles',$productInfo['articles']],['company',$this->config['company']]])->first();
                        if(isset($product)){
                            $this->stockCountProduct($product,$productInfo);
                        }
                        $this->count_success++;
                    } else {
                        $product = new Product();
                        $product->fill($array_import);
                        if ($product->save()){
                            $this->stockCountProduct($product,$productInfo);
                            $this->count_success++;
                        } else{
                            $this->count_fail++;
                        }
                    }

                }catch (\Exception $e){
                    if (config('app.debug')){
                        dump("Error import : $e");
                    } else {
                        Log::error("Error import : $e");
                    }
                    $this->count_fail++;
                }
            }
        }
    }

    protected function stockCountProduct($product,$data){
        foreach ($this->stocks as $k => $stock){
            foreach($data['count'] as $key => $item){
                if($k === $key){
                    if(DB::table('stock_products')->where([['stock_id',$stock->id],['product_id',$product->id]])->exists()){
                        DB::table('stock_products')->where([['stock_id',$stock->id],['product_id',$product->id]])
                            ->update(['count' => (int)str_replace('>','',$data['count'][$key])]);
                    } else {
                        DB::table('stock_products')->insert([
                            'stock_id' => $stock->id,
                            'product_id' => $product->id,
                            'count' => (int)str_replace('>','',$data['count'][$key])
                        ]);
                    }
                }
            }
        }
    }

    public function transliterateRU($sts){
        $rus = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ');
        $lat = array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ');
        $transliterate_str = str_replace($rus, $lat, $sts);
        return $transliterate_str;
    }
}