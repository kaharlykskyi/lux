<?php

namespace App\Http\Controllers;

use App\Services\VinDecoder;
use Exception;
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
        return view('vin_decode.index',compact('vin','response'));
    }

    public function catalog(Request $request){
        try{
            $data = file_get_contents('https://exist.ua/api/v1/laximo/oem/groups/?' . $request->getQueryString() . 'quick_group_id=');
            $response = json_decode($data);
        }catch (Exception $exception){
            $response = [];
        }
        return view('vin_decode.catalog_group',compact('response'));
    }

    public function page(Request $request){
        $oem_info = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/info/?' . $request->getQueryString()));
        $oem_detail_unit = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/detail/unit/?' . $request->getQueryString()));
        return view('vin_decode.page',compact('oem_info','oem_detail_unit'));
    }

    public function pageData(){
        $data = session('page-data');
        return view('vin_decode.frame_detal',compact('data'));
    }

    public function quickGroup(Request $request){
        return response(
            $this->service
                ->getAjaxData('https://exist.ua/api/v1/laximo/oem/detail/?' . $request->getQueryString())
        );
    }

    public function vinCar(Request $request){
        return response(
            $this->service
                ->getAjaxData('https://exist.ua/api/v1/laximo/oem/vehicle/?catalog=&task=vin&vin=' . $request->vin)
        );
    }
}
