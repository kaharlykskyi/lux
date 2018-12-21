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
}
