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
        if ($request->task === 'qdetails'){
            try{
                $data = file_get_contents('https://exist.ua/api/v1/laximo/oem/groups/?' . $request->getQueryString() . 'quick_group_id=');
                $response = json_decode($data);
            }catch (Exception $exception){
                $response = [];
            }
            return view('vin_decode.catalog_group',compact('response'));
        }elseif ($request->task === 'units'){
            try{
                $categories = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/categories/?'. $request->getQueryString() . '&category_id=-1'));
                $list_units = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/detail/list_unit/?' . $request->getQueryString() . '&category_id=-1'));
                $sort_categories = ['root' => [],'child' => []];
                $search_info = $categories->data->search_info;

                foreach ($categories->data->list as $row){
                    $row_arr = get_object_vars($row);
                    if ($row_arr['@childrens'] === 'true'){
                        $sort_categories['root'][] = $row_arr;
                    }else{
                        $sort_categories['child'][$row_arr['@parentcategoryid']][] = $row_arr;
                    }
                }

            }catch (Exception $exception){
                $sort_categories = [];
                $list_units = [];
            }
            return view('vin_decode.catalog_units',compact('list_units','sort_categories','search_info'));
        }
        return abort(404);
    }

    public function page(Request $request){
        try{
            $oem_info = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/info/?' . $request->getQueryString()));
            $oem_detail_unit = json_decode(file_get_contents('https://exist.ua/api/v1/laximo/oem/detail/unit/?' . $request->getQueryString()));
            return view('vin_decode.page',compact('oem_info','oem_detail_unit'));
        }catch (Exception $exception){
            return back()->with('status','Произошла ошибка');
        }
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

    public function units(Request $request){
        return response()->json([
            'list_units' => json_decode($this->service->getAjaxData('https://exist.ua/api/v1/laximo/oem/detail/list_unit/?' . $request->getQueryString())),
            'categories' => json_decode($this->service->getAjaxData('https://exist.ua/api/v1/laximo/oem/categories/?' . $request->getQueryString()))
        ]);
    }
}
