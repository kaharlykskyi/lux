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

    protected $product_data = [];

    protected $count_success = 0;

    protected $count_fail = 0;

    protected $currency = null;

    public function __construct()
    {
        ini_set('memory_limit', '4090M');
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
                            $this->product_data = [];
                        }

                        $mailbox->deleteMail($mailsId);
                    }
                }
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
                            if ($row->getRowIndex() >= $this->config['data_row'] ){
                                if (isset($this->config['cells'][$cellPath])){
                                    $this->product_data[$row->getRowIndex()][$this->config['cells'][$cellPath]] = $cell->getCalculatedValue();
                                }
                                if ($this->config['stock_data_one_row']){
                                    if (isset($this->config['stocks'][$cellPath])){
                                        $this->product_data[$row->getRowIndex()][$this->config['stocks'][$cellPath]] = (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                    }
                                }else{
                                    if ( in_array($cellPath,$this->config['stocks'])){
                                        if (isset($this->product_data[$row->getRowIndex()]['count'])){
                                            $this->product_data[$row->getRowIndex()]['count'] += (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                        } else {
                                            $this->product_data[$row->getRowIndex()]['count'] = (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                        }
                                    }
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

                    if ((float)$productInfo['price'] < 2000){
                        $productInfo['price'] = (float)$productInfo['price'] + (float)($productInfo['price'] * 0.2);
                    } elseif ((float)$productInfo['price'] >= 2000 && (float)$productInfo['price'] <= 5000){
                        $productInfo['price'] = (float)$productInfo['price'] + (float)($productInfo['price'] * 0.15);
                    } elseif((float)$productInfo['price'] > 5000){
                        $productInfo['price'] = (float)$productInfo['price'] + (float)($productInfo['price'] * 0.1);
                    }

                    $array_import = [
                        'name' => $productInfo['name'],
                        'articles' => $productInfo['articles'],
                        'brand' => $productInfo['brand'],
                        'short_description' => isset($productInfo['short_description'])? $productInfo['short_description']: null,
                        'full_description' => isset($productInfo['full_description'])? $productInfo['full_description']: null,
                        'price' => round($productInfo['price'],2),
                        'company' => $this->config['company'],
                        'old_price' => isset($productInfo['old_price'])? round($productInfo['old_price'],2): null,
                        'count' => isset($productInfo['count'])? $productInfo['count']: 0,
                    ];

                    if(DB::table('products')->where([['articles',$productInfo['articles']],['company',$this->config['company']]])->exists()){
                        Product::where([['articles',$productInfo['articles']],['company',$this->config['company']]])
                            ->update($array_import);
                        $this->count_success++;
                    } else {
                        $product = new Product();
                        $product->fill($array_import);
                        if ($product->save()){
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
}