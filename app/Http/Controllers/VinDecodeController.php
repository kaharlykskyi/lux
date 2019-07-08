<?php

namespace App\Http\Controllers;

use App\Services\VinDecoder;
use Illuminate\Http\{Request, Response};
use Symfony\Component\DomCrawler\Crawler;

class VinDecodeController extends Controller
{

    protected $search_data = [];

    protected $base_url = 'https://exist.ua';

    protected $vin_cat_url = 'https://exist.ua/cat/oe/';

    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new VinDecoder();
    }

    public function index(Request $request){
        $vin = $request->post('vin');
        if (isset($vin)){
            $html = file_get_contents($this->vin_cat_url . '?action=vehicleVIN&infoVIN=' . $vin);
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

        if ($request->has('vin_catalog_type') && $request->ajax()){
            $response = new Response();
            $response->withCookie(cookie()->forever('vin_catalog',$request->vin_catalog_type));
            return $response;
        }

        if ($request->hasCookie('vin_catalog')){
            $type_catalog = $request->cookie('vin_catalog');
        } else {
            $type_catalog = 'quickGroup';
        }

        $data = $request->except('_token');
        $vin = $data['vin_code'];
        $vin_title = $data['vin_title'];

        if ($type_catalog === 'listUnits'){
            $response_data = $this->service->getCatalogForImage($data);
            $catalog_data = $response_data['catalog_data'];
            $category = $response_data['category'];
            return view('vin_decode.catalog_img', compact('catalog_data','vin','vin_title','category'));
        } else {
            try{
                $response_data = $this->service->getCatalogForGroup($data);
                $category = $response_data['category'];
                return view('vin_decode.catalog_group',compact('vin','vin_title','category'));
            }catch (\Exception $exception){
                $response_data = $this->service->getCatalogForImage($data);
                $catalog_data = $response_data['catalog_data'];
                $category = $response_data['category'];
                $show_nav = false;
                return view('vin_decode.catalog_img', compact('catalog_data','vin','vin_title','category','show_nav'));
            }

        }


    }

    public function page(Request $request){
        $data = $request->except('_token');
        $vin = $data['vin_code'];
        $vin_title = $data['vin_title'];

        $data['data'] = preg_replace('/&comment=(.)*$/mixs','',$data['data']);

        $html = file_get_contents($this->vin_cat_url . $data['data']);
        $crawler = new Crawler($html);

        $head = $crawler->filterXPath('.//head');

        $crawler->filter('div.page-blocks.page-blocks--padding.page-content-wrapper > div.w100')->each(function (Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $crawler->filter('div.page-blocks.page-blocks--padding.page-content-wrapper div.page-blocks.page-blocks--padding.page-content-wrapper div > table.w50')->each(function (Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        $body = $crawler->filter('div.page-blocks.page-blocks--padding.page-content-wrapper div.page-blocks.page-blocks--padding.page-content-wrapper');
        foreach ($body->filter('div#viewtable table a') as $item){
            $buff = $item->getAttribute('href');
            $buff = explode('?',$buff,2);
            $item->setAttribute('href', route('catalog'). '?' .$buff[1]);
        }
        $data = [
            'head' => $head->html(),
            'body' => $body->html()
        ];

        session(['page-data' => $data]);
        return view('vin_decode.page', compact('vin','vin_title'));
    }

    public function pageData(){
        $data = session('page-data');
        return view('vin_decode.frame_detal',compact('data'));
    }

    public function ajaxData(Request $request){
        $data = $request->except('_token');

        return response($this->service->getAjaxData($data));
    }
}
