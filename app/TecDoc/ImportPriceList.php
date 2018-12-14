<?php

namespace App\TecDoc;


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

    public function __construct()
    {
        $this->getMail();
    }

    protected function getMail(){
        $price_list_configs = config('price_list_settings');
        foreach ($price_list_configs as $config){
            $this->config = $config;
            $connect_to = '{imap.gmail.com:993/imap/ssl}INBOX';
            $user = $this->config['email'];
            $password = $this->config['password'];

            try{

                $mailbox = new Mailbox($connect_to,$user,$password, storage_path('app') . '\price_list');

                $mailsIds = $mailbox->searchMailbox('ALL');

                if(!$mailsIds) continue;

                foreach ($mailsIds as $mailsId){
                    $mail = $mailbox->getMail($mailsId);
                    foreach ($mail->getAttachments() as $mailAttachment){
                        $this->export($mailAttachment->filePath);
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
                        }
                    }
                }

                dump($this->stocks);

            } catch (\PHPExcel_Reader_Exception $e) {
                if (config('app.debug')){
                    dump("Error load '$file': $e");
                } else {
                    Log::error("Error load '$file': $e");
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
}