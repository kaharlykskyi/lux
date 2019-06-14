<?php

namespace App\Services\Admin;


use Illuminate\Support\Facades\DB;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet_PageSetup;

class Product
{
    public function getExportData($limit = 10){
        return DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'),DB::raw('al.DataSupplierArticleNumber'),DB::raw('p.articles'))
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),'sp.matchcode','=','p.brand')
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.prd AS prd'),DB::raw('prd.id'),DB::raw('al.linkageid'))
            ->select(DB::raw('p.name, p.articles, p.price, p.brand, p.count, prd.normalizeddescription, al.SupplierId,
                        (SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=sp.id LIMIT 1) AS PictureName
                    '))
            ->limit($limit)
            ->get();
    }

    public function getAttribute($article,$supplierId){
        return DB::connection('mysql_tecdoc')->table('article_attributes')
            ->where('supplierId',$supplierId)
            ->where('DataSupplierArticleNumber',$article)
            ->get(['description','displayvalue']);
    }

    public function createXlsFile($data){

        $objPHPExcel = new PHPExcel();
        try {
            $objPHPExcel->setActiveSheetIndex(0);
            $active_sheet = $objPHPExcel->getActiveSheet();

            $active_sheet->getPageSetup()
                ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $active_sheet->getPageSetup()
                ->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            $active_sheet->getPageMargins()->setTop(1);
            $active_sheet->getPageMargins()->setRight(0.75);
            $active_sheet->getPageMargins()->setLeft(0.75);
            $active_sheet->getPageMargins()->setBottom(1);

            $active_sheet->setTitle("Прайс-лист " . config('app.name'));
            $active_sheet->getHeaderFooter()->setOddHeader("&C".$active_sheet->getTitle());
            $active_sheet->getHeaderFooter()->setOddFooter('&L&B'.$active_sheet->getTitle().'&RСтраница &P из &N');

            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);

            $date = date('d-m-Y');
            $active_sheet->setCellValue('L1','дата генерации');
            $active_sheet->setCellValue('L2',$date);
            $active_sheet->getStyle('L2')
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);

            $active_sheet->setCellValue('A1','артикул');
            $active_sheet->setCellValue('B1','наименование');
            $active_sheet->setCellValue('C1','фото');
            $active_sheet->setCellValue('D1','цена');
            $active_sheet->setCellValue('E1','наличие');
            $active_sheet->setCellValue('F1','фирма производитель');
            $active_sheet->setCellValue('G1','группа товаров на сайте');
            $active_sheet->setCellValue('H1','группа товаров на портале пром юа');
            $active_sheet->setCellValue('I1','характеристики');
            $active_sheet->setCellValue('J1','валюта');
            $active_sheet->setCellValue('K1','единици');

            $row_start = 2;
            $i = 0;

            foreach ($data as $item){
                $row_next = $row_start + $i;

                if (isset($item->PictureName)){
                    $file_brand = explode('_',$item->PictureName);
                    $file = asset('product_imags/'.$file_brand[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$item->PictureName));
                }else{
                    $file = asset('images/default-no-image_2.png');
                }

                $active_sheet->setCellValue('A' .$row_next,$item->articles);
                $active_sheet->setCellValue('B' .$row_next,$item->name);
                $active_sheet->setCellValue('C' .$row_next,$file);
                $active_sheet->setCellValue('D' .$row_next,$item->price);
                $active_sheet->setCellValue('E' .$row_next,$item->count);
                $active_sheet->setCellValue('F' .$row_next,$item->brand);
                $active_sheet->setCellValue('G' .$row_next,$item->normalizeddescription);
                $active_sheet->setCellValue('H' .$row_next,$item->normalizeddescription);

                $attr_str = '';
                foreach ($item->attribute as $val){
                    $attr_str .= "{$val->description} - {$val->displayvalue} \n";
                }
                $active_sheet->setCellValue('I' .$row_next,$attr_str);

                $active_sheet->setCellValue('J' .$row_next,'UAH');
                $active_sheet->setCellValue('K' .$row_next,'шт.');

                $i++;
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            $file_name = 'export_'. time() . '.xls';
            $objWriter->save(storage_path('app') . '/export_price_list/'. $file_name);

            return storage_path('app') . '/export_price_list/'. $file_name;


        } catch (\PHPExcel_Exception $e) {
            return ['error' => $e];
        }
    }
}
