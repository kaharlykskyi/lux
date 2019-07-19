<?php

namespace App\TecDoc;


use App\{AliasBrand, NoBrandProduct, Product, ProFile};
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\{Carbon, Facades\DB, Facades\Log};
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Reader_CSV;
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

    protected $tecdoc;

    protected $tectdoc_suppliers;

    protected $tecdoc_manufacturers;

    protected $alias_brands;

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

        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');

        $this->tecdoc_manufacturers = $this->tecdoc->getBrands();
        $this->tectdoc_suppliers = $this->tecdoc->getAllSuppliers();

        $this->profile = ProFile::with('provider')->get();
        $this->alias_brands = AliasBrand::all();

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
                            if (stristr($mailAttachment->filePath,$config->static_name)){
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

                $stock_cells = explode(',',$this->config->stocks);
                $stock_cells_sort = [];
                foreach ($stock_cells as $stock_cell){
                    $buff = explode('/',$stock_cell);
                    $stock_cells_sort[] = [
                        'count' => $buff[0],
                        'stock' => isset($buff[1])?$buff[1]:null
                    ];
                }

                $csv_file = preg_match('/\.csv$/i',$file)?true:false;
                if ($csv_file){
                    $file_read = fopen($file,'r');
                    $key = 0;
                    while (($column = fgetcsv($file_read)) !== FALSE) {
                        try{
                            $str = mb_convert_encoding($column[0], "utf-8", "windows-1251");
                            $data = explode("\t",$str);
                            if (!isset($data[1])){
                                $data = explode(",",$str);
                            }
                            if (!isset($data[1])){
                                $data = explode(";",$str);
                            }
                            if ((int)$this->config->data_row < $key + 1){
                                $this->product_data[$key]['articles'] = str_replace('"','',$data[(int)$this->config->articles - 1]);
                                $this->product_data[$key]['product_name'] = str_replace('"','',$data[(int)$this->config->product_name - 1]);
                                $this->product_data[$key]['brand'] = str_replace('"','',$data[(int)$this->config->brand - 1]);
                                $this->product_data[$key]['price'] = str_replace('"','',$data[(int)$this->config->price - 1]);
                                $this->product_data[$key]['delivery_time'] = isset($this->config->delivery_time)?$data[(int)$this->config->delivery_time - 1]:0;

                                foreach ($stock_cells_sort as $stock_cell){
                                    if (isset($this->product_data[$key]['count'])){
                                        $this->product_data[$key]['count'] += (int)preg_replace("/[^0-9,.]/", "", $data[(int)$stock_cell['count'] - 1]);
                                    } else {
                                        $this->product_data[$key]['count'] = (int)preg_replace("/[^0-9,.]/", "", $data[(int)$stock_cell['count'] - 1]);
                                    }
                                    $this->product_data[$key]['stocks'][$stock_cell['stock']] = (int)preg_replace("/[^0-9,.]/", "", $data[(int)$stock_cell['count'] - 1]);
                                }
                            }
                            $key++;
                        }catch (Exception $exception){
                            Log::error($exception);
                            $this->count_fail++;
                        }
                    }
                }else{
                    $this->xls = PHPExcel_IOFactory::load($file);
                    $this->xls->setActiveSheetIndex((int)$this->config->active_sheet - 1);
                    $sheet = $this->xls->getActiveSheet();
                    $rowIterator = $sheet->getRowIterator();
                    foreach ($rowIterator as $key => $row){
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

                                foreach ($stock_cells_sort as $stock_cell){
                                    if ($cellPath === $stock_cell['count']){
                                        if (isset($this->product_data[$row->getRowIndex()]['count'])){
                                            $this->product_data[$row->getRowIndex()]['count'] += (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                        } else {
                                            $this->product_data[$row->getRowIndex()]['count'] = (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                        }
                                        if (isset($stock_cell['stock'])){
                                            $this->product_data[$row->getRowIndex()]['stocks'][$stock_cell['stock']] = (int)preg_replace("/[^0-9,.]/", "", $cell->getCalculatedValue());
                                        }
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
                $is_supplier = false;
                $is_original = false;

                $productInfo['brand'] = strtolower(trim($productInfo['brand']));

                foreach ($this->alias_brands as $item){
                    if ($productInfo['brand'] == strtolower($item->name)){
                        $productInfo['brand'] = $item->tecdoc_name;
                    }
                }

                foreach ($this->tectdoc_suppliers as $item){
                    if ($productInfo['brand'] == strtolower($item->matchcode) || $productInfo['brand'] == strtolower($item->description)){
                        $productInfo['brand'] = $item->id;
                        $is_supplier = true;
                    }
                }

                if (!$is_supplier){
                    foreach ($this->tecdoc_manufacturers as $item){
                        if ($productInfo['brand'] == strtolower($item->description) || $productInfo['brand'] == strtolower($item->matchcode)){
                            $productInfo['brand'] = $item->id;
                            $is_original = true;
                        }
                    }
                }

                $productInfo['price'] = floatval($productInfo['price']);
                $productInfo['provider_price'] = $productInfo['price'];

                if((isset($this->config->currency) || isset($this->config->provider->currency)) && isset($this->currency)){
                    if (isset($this->config->currency) && $this->config->currency !== 'UAH'){
                        foreach ($this->currency as $item){
                            if ($this->config->currency === $item->ccy){
                                $item->sale = isset($this->config->exchange_range)?$this->config->exchange_range:$item->sale;
                                $productInfo['price'] = $productInfo['price'] * (float)$item->sale;
                                if (isset($productInfo['old_price'])){
                                    $productInfo['old_price'] = floatval($productInfo['old_price']) * (float)$item->sale;
                                }
                            }
                        }
                    } elseif (isset($this->config->provider->currency) && $this->config->provider->currency !== 'UAH'){
                        foreach ($this->currency as $item){
                            if ($this->config->provider->currency === $item->ccy){
                                $item->sale = isset($this->config->exchange_range)?$this->config->exchange_range:$item->sale;
                                $productInfo['price'] = (float)$productInfo['price'] * (float)$item->sale;
                                if (isset($productInfo['old_price'])){
                                    $productInfo['old_price'] = floatval($productInfo['old_price']) * (float)$item->sale;
                                }
                            }
                        }
                    }
                }
                try{

                    $markup_data = isset($this->config->markup)?json_decode($this->config->markup):null;
                    if (isset($markup_data) && !empty($markup_data)){
                        foreach ($markup_data as $val){
                            if ($val->min < $productInfo['price'] && $productInfo['price'] < $val->max){
                                $productInfo['price'] = $productInfo['price'] + ($productInfo['price'] * $val->markup / 100);
                            }
                        }
                    }else{
                        if ($productInfo['price'] < 2000){
                            $productInfo['price'] = $productInfo['price'] + ($productInfo['price'] * 0.2);
                        } elseif ($productInfo['price'] >= 2000 && $productInfo['price'] <= 5000){
                            $productInfo['price'] = $productInfo['price'] + ($productInfo['price'] * 0.15);
                        } elseif($productInfo['price'] > 5000){
                            $productInfo['price'] = $productInfo['price'] + ($productInfo['price'] * 0.1);
                        }
                    }

                    $productInfo['price'] = $productInfo['price'] < 1?1:$productInfo['price'];

                    $array_import = [
                        'name' => $productInfo['product_name'],
                        'articles' => trim($productInfo['articles']),
                        'brand' => $productInfo['brand'],
                        'short_description' => isset($productInfo['short_description'])? $productInfo['short_description']: null,
                        'full_description' => isset($productInfo['full_description'])? $productInfo['full_description']: null,
                        'price' => round($productInfo['price'],2),
                        'provider_id' => isset($this->config->provider_id)?$this->config->provider_id:null,
                        'old_price' => isset($productInfo['old_price'])? round($productInfo['old_price'],2): null,
                        'count' => isset($productInfo['count'])? $productInfo['count']: 0,
                        'delivery_time' => isset($productInfo['delivery_time'])?(int)preg_replace("/[^0-9,.]/", "", $productInfo['delivery_time']):0,
                        'provider_price' => $productInfo['provider_price'],
                        'provider_currency' => isset($this->config->provider->currency)?$this->config->provider->currency:'UAH',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'stocks' => isset($productInfo['stocks'])?json_encode($productInfo['stocks']):null,
                        'original' => $is_original?1:0
                    ];



                    if ($is_original || $is_supplier){
                        $insert_data = DB::table('products')->updateOrInsert(
                            ['articles' => $array_import['articles'], 'provider_id' => $array_import['provider_id']],
                            $array_import
                        );
                        if ($insert_data){
                            $this->count_success++;
                        } else {
                            $this->count_fail++;
                        }
                    }else{
                        $insert_data = DB::table('no_brand_products')->updateOrInsert(
                            ['articles' => $array_import['articles'], 'provider_id' => $array_import['provider_id']],
                            $array_import
                        );
                        if ($insert_data){
                            $this->count_success++;
                        } else {
                            $this->count_fail++;
                        }
                    }

                }catch (Exception $e){
                    Log::error("Error import : $e");
                    $this->count_fail++;
                }
            }
        }
    }
}
