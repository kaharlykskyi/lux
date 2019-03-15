<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;


class VinDecoder
{


    protected $base_url = 'https://exist.ua';

    protected $vin_cat_url = 'https://exist.ua/cat/oe/';

    public function getCatalogForImage($data){
        $catalog_data = null;
        $data['data'] = str_replace('quickGroup','listUnits',$data['data']);

        $html = file_get_contents($this->vin_cat_url . $data['data']);
        $crawler = new Crawler($html);

        $catalog_html = $crawler->filter('div.guayaquil_floatunitlist_box');
        $img_html = $catalog_html->filterXPath(".//div[@class='g-highlight']//img");
        foreach ($img_html as $k => $node){
            $catalog_data['img_small'][] = $node->getAttribute('src');
        }
        $img_full_html = $catalog_html->filterXPath(".//div[@class='guayaquil-unit-icons']/div");
        foreach ($img_full_html as $k => $node){
            $catalog_data['img_full'][] = $node->getAttribute('full');
        }
        $catalog_title = $catalog_html->filterXPath(".//div[@class='g-highlight']//td[@class='guayaquil_floatunitlist_title']//a");
        foreach ($catalog_title as $node){
            $catalog_data['catalog_title'][] = $node->textContent;
        }
        foreach ($catalog_title as $node){
            $catalog_data['catalog_link'][] = $node->getAttribute('href');
        }

        $category = null;
        $category_html = $crawler->filter('div.guayaquil_categoryfloatbox');

        $category_block = $category_html->filter("div.guayaquil_categoryitem_parent > a:last-child");
        try{
            $category_block->first()->attr('href');
        }catch (\Exception $e){
            $category_block = $category_html->filter("div.guayaquil_categoryitem > a:last-child");
        }

        foreach ($category_block as $node){
            $category['category_link'][] = $node->getAttribute('href');
        }
        foreach ($category_block as $node){
            $category['category_title'][] = $node->textContent;
        }

        return [
            'catalog_data' => $catalog_data,
            'category' => $category
        ];
    }

    public function getCatalogForGroup($data){
        $catalog_data = null;

        $html = file_get_contents($this->vin_cat_url . $data['data']);
        $crawler = new Crawler($html);

        $category_html = $crawler->filter('div#qgTree')->html();

        return [
            'category' => $category_html
        ];
    }

    public function getAjaxData($data){
        $ch = curl_init();
        $postvars = '';
        foreach($data as $key => $value) {
            $postvars .= $key . "=" . $value . "&";
        }
        curl_setopt($ch,CURLOPT_URL,$this->base_url . '/ajaxQuery.php');
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded'
        ));
        $response = curl_exec($ch);
        curl_close ($ch);
        return $response;
    }
}