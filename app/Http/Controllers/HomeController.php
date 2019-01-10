<?php

namespace App\Http\Controllers;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $tecdoc;

    protected $data = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index()
    {

        $this->tecdoc->setType('passenger');
        //dump($this->tecdoc->getModels(771,'2007'));
        //dump($this->tecdoc->getModifications(4955));
        return view('home.index');
    }

    public function subcategory(Request $request){
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        return response()->json([
            'subCategory' => $this->tecdoc->getPrd(4,$request->category)
        ]);
    }

    public function getBrands(Request $request){
        if (isset($request->type_auto)){
            $this->tecdoc->setType($request->type_auto);
            return response()->json([
                'response' => $this->tecdoc->getBrands()
            ]);
        } else {
            return response()->json([
                'response' => [
                    'id' => 0,
                    'description' => 'не найдено'
                ]
            ]);
        }
    }

    public function getModel(Request $request){
        if (isset($request->type_auto)){
            $this->tecdoc->setType($request->type_auto);
            $buff = $this->tecdoc->getModels($request->brand_id);
            if (isset($request->year_auto)){
                $model = [];
                foreach ($buff as $k => $item){
                    $constructioninterval = explode(' - ',$item->constructioninterval);
                    $start = ($constructioninterval[0] !== '') ? explode('.',$constructioninterval[0]): null;
                    $end = ($constructioninterval[1] !== '') ? explode('.',$constructioninterval[1]): null;
                    if (isset($start) && isset($end)){
                        if ((int)$request->year_auto > (int)$start[1] && (int)$request->year_auto < (int)$end[1]){
                            $model[] = $item;
                        }
                    } elseif (isset($start) && !isset($end)){
                        if ((int)$request->year_auto > (int)$start[1]){
                            $model[] = $item;
                        }
                    }
                }
            } else {
                $model = $buff;
            }


            return response()->json([
                'response' => $model
            ]);
        } else {
            return response()->json([
                'response' => [
                    'id' => 0,
                    'description' => 'не найдено'
                ]
            ]);
        }
    }

    public function getModifications(Request $request){
        if (isset($request->type_auto)){
            $this->tecdoc->setType($request->type_auto);
            switch ($request->type_mod){
                case 'General':
                    $this->data = $this->tecdoc->getModifications($request->model_id,$request->type_mod);
                    break;
                case 'Engine':
                    $buff = $this->tecdoc->getModifications($request->model_id,'TechnicalData','FuelType');
                    $use_val = [];
                    foreach ($buff as $item){
                        if(!in_array($item->displayvalue,$use_val)){
                            $use_val[] = $item->displayvalue;
                            $this->data[] = $item;
                        }
                    }
                    break;
                case 'Body':
                    $buff = $this->tecdoc->getModifications($request->model_id,$request->type_mod,'BodyType');
                    $use_val = [];
                    foreach ($buff as $item){
                        if(!in_array($item->displayvalue,$use_val)){
                            $use_val[] = $item->displayvalue;
                            $this->data[] = $item;
                        }
                    }
                    break;
            }
            return response()->json([
                'response' => $this->data
            ]);
        } else {
            return response()->json([
                'response' => [
                    'id' => 0,
                    'description' => 'не найдено'
                ]
            ]);
        }
    }
}
