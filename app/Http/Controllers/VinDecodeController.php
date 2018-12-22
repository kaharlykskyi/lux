<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class VinDecodeController extends Controller
{

    protected $search_data = [];

    protected $base_url = 'https://exist.ua';

    public function index(Request $request){
        $vin = $request->post('vin');
        if (isset($vin)){
            $html = file_get_contents('https://exist.ua/cat/oe/?action=vehicleVIN&infoVIN=' . $vin);
            $crawler = new Crawler($html);
            try{
                //img
                foreach ($crawler->filter('div.result-title')->children('img') as $node){
                    $this->search_data['img']= $this->base_url . $node->getAttribute('src');
                }
                //title
                foreach($crawler->filter('div.result-title')->children('a') as $node){
                    $this->search_data['title'] = $node->nodeValue;
                }
                //data
                $data_array = null;
                $data_html = $crawler->filterXPath('//table/tr');
                foreach ($data_html as $k => $node){
                    $tds = array();
                    $node_html = new Crawler($node);
                    if(count($data_html) - 1 !== $k ){
                        foreach ($node_html->filter('td') as $item){
                            $tds[] = $item->nodeValue;
                        }
                    } else {
                        foreach ($node_html->filter('td > a') as $item){
                            $tds[] = $item->getAttribute('href');
                        }
                    }
                    $data_array[] = $tds;
                }
                return view('vin_decode.index')->with([
                    'vin' => $vin,
                    'search_data' => $this->search_data,
                    'data_array' => $data_array
                ]);

            } catch (\Exception $exception){
                return redirect()->route('vin_decode')->with([
                    'vin' => $vin,
                    'status' => 'Нету результатов'
                ]);
            }

        }

        return view('vin_decode.index');
    }

    public function catalog(Request $request){
        $data = $request->except('_token');
        $vin = $data['vin_code'];
        $vin_title = $data['vin_title'];
        $catalog_data = null;
        $data['data'] = str_replace('quickGroup','listUnits',$data['data']);

        $html = file_get_contents('https://exist.ua/cat/oe/' . $data['data']);
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
        foreach ($category_block as $node){
            $category['category_link'][] = $node->getAttribute('href');
        }
        foreach ($category_block as $node){
            $category['category_title'][] = $node->textContent;
        }

        return view('vin_decode.catalog', compact('catalog_data','vin','vin_title','category'));
    }

    public function page(){

    }
}
