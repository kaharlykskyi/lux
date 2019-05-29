<?php

namespace App\TecDoc;


use App\{Product, ProFile};
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\{Carbon, Facades\DB, Facades\Log};
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use PhpImap\Mailbox;

class ImportPriceList
{

    protected $xls = null;

    protected $config = null;

    protected $product_data = [];

    protected $count_success = 0;

    protected $count_fail = 0;

    protected $currency = null;

    protected $profile = null;

    public function __construct($data = null,$ease = false)
    {
        ini_set('memory_limit', '4090M');
        try{
            $client = new Client([
                'headers' => [
                    "cache-control: no-cache"
                ]
            ]);
            $this->currency = json_decode($client->get('https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11')->getBody());
        } catch (Exception $e){
            if (config('app.debug')){
                dump("Can't connect to privatbank: $e");
            } else {
                Log::error("Can't connect to privatbank: $e");
            }
        }

        $this->profile = ProFile::with('provider')->get();

        if ($ease){
            $this->easeImport($data);
        }else{
            $this->getMail();
        }

    }

    protected function easeImport($data){
        if (isset($data->company) && (int)$data->company > 0){
            $profiles = ProFile::with('provider')->where('provider_id',(int)$data->company)->get();
        } else {
            $profiles = ProFile::with('provider')->get();
        }

        foreach ($profiles as $config){
            if (isset($data->company) && (int)$data->company > 0 && $config->provider->id === (int)$data->company){
                $this->startImport($config,$data->file);
            } elseif (stristr($data->file,$config->static_name)){
                $this->startImport($config,$data->file);
            } else {
                DB::table('history_imports')->insert([
                    'company' => '<a href="'.route('admin.incognito',$data->file).'">Файл</a> не распознан',
                    'success' => 0,
                    'fail' => 0,
                    'created_at' => Carbon::now()
                ]);
            }
        }
    }

    private function startImport($config,$file){
        $this->config = $config;
        $this->export(storage_path('app') . '/import_ease/' . $file);

        DB::table('history_imports')->insert([
            'company' => isset($config->provider)?$config->provider->name:$config->name,
            'success' => $this->count_success,
            'fail' => $this->count_fail,
            'created_at' => Carbon::now()
        ]);

        unlink(storage_path('app') . '/import_ease/' . $file);
    }

    protected function getMail(){
        try{
            $connect_to = '{imap.gmail.com:993/imap/ssl}INBOX';
            $user = config('app.work_mail');
            $password = config('app.work_pass');

            $mailbox = new Mailbox($connect_to,$user,$password, storage_path('app') . '/price_list');

            $mailsIds = $mailbox->searchMailbox('UNSEEN');

            if(!$mailsIds) return;

            foreach ($mailsIds as $mailsId){
                $mail = $mailbox->getMail($mailsId);
                foreach ($this->profile as $config){
                    $this->config = $config;
                    if($mail->fromAddress === $config->static_email1 || $mail->fromAddress === $config->static_email2 || $mail->fromAddress === $config->provider->email){

                        foreach ($mail->getAttachments() as $mailAttachment){
                            $this->export($mailAttachment->filePath);

                            DB::table('history_imports')->insert([
                                'company' => isset($config->provider)?$config->provider->name:$config->name,
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

        } catch (Exception $exception){
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
                $stock_cells = explode(',',$this->config->stocks);

                foreach ($rowIterator as $row){
                    $cellIterator = $row->getCellIterator();
                    foreach ($cellIterator as $cell){
                        $cellPath = $cell->getColumn();
                        if ($row->getRowIndex() >= (int)$this->config->data_row ){
                            if ($cellPath === $this->config->articles){
                                $this->product_data[$row->getRowIndex()]['articles'] = $cell->getCalculatedValue();
                            }
                            if ($cellPath === $this->config->product_name){
                                $this->product_data[$row->getRowIndex()]['product_name'] = $cell->getCalculatedValue();
                            }
                            if ($cellPath === $this->config->brand){
                                $this->product_data[$row->getRowIndex()]['brand'] = $cell->getCalculatedValue();
                            }
                            if ($cellPath === $this->config->price){
                                $this->product_data[$row->getRowIndex()]['price'] = $cell->getCalculatedValue();
                            }
                            if ($cellPath === $this->config->delivery_time){
                                $this->product_data[$row->getRowIndex()]['delivery_time'] = $cell->getCalculatedValue();
                            }

                            foreach ($stock_cells as $stock_cell){
                                if ($cellPath === $stock_cell){
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
                $this->productQuery();

            } catch (PHPExcel_Reader_Exception $e) {
                if (config('app.debug')){
                    dump("Error load '$file': $e");
                } else {
                    Log::error("Error load '$file': $e");
                }

                return false;
            } catch (Exception $e){
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
                $productInfo['provider_price'] = $productInfo['price'];
                if((isset($this->config->currency) || isset($this->config->provider->currency)) && isset($this->currency)){
                    if (isset($this->config->currency) && $this->config->currency !== 'UAH'){
                        foreach ($this->currency as $item){
                            if ($this->config->currency === $item->ccy){
                                $productInfo['price'] = (float)$productInfo['price'] * (float)$item->sale;
                                if (isset($productInfo['old_price'])){
                                    $productInfo['old_price'] = (float)$productInfo['old_price'] * (float)$item->sale;
                                }
                            }
                        }
                    } elseif (isset($this->config->provider->currency) && $this->config->provider->currency !== 'UAH'){
                        foreach ($this->currency as $item){
                            if ($this->config->provider->currency === $item->ccy){
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

                    $productInfo['price'] = $productInfo['price'] < 1?1:$productInfo['price'];

                    $array_import = [
                        'name' => $productInfo['product_name'],
                        'articles' => $productInfo['articles'],
                        'brand' => $productInfo['brand'],
                        'short_description' => isset($productInfo['short_description'])? $productInfo['short_description']: null,
                        'full_description' => isset($productInfo['full_description'])? $productInfo['full_description']: null,
                        'price' => round($productInfo['price'],2),
                        'provider_id' => isset($this->config->provider_id)?$this->config->provider_id:null,
                        'old_price' => isset($productInfo['old_price'])? round($productInfo['old_price'],2): null,
                        'count' => isset($productInfo['count'])? $productInfo['count']: 0,
                        'delivery_time' => isset($productInfo['delivery_time'])?(int)preg_replace("/[^0-9,.]/", "", $productInfo['delivery_time']):0,
                        'provider_price' => $productInfo['provider_price'],
                        'provider_currency' => isset($this->config->provider->currency)?$this->config->provider->currency:'UAH'
                    ];

                    $insert_data = Product::updateOrInsert(
                        ['articles' => $array_import['articles'], 'provider_id' => $array_import['provider_id']],
                        $array_import
                    );

                    if ($insert_data){
                        $this->count_success++;
                    } else {
                        $this->count_fail++;
                    }

                }catch (Exception $e){
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
