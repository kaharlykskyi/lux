<?php

namespace App\Http\Controllers;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index()
    {

        $this->tecdoc->setType('passenger');
        //dump($this->tecdoc->getModels(771,'2007'));
        //dump($this->tecdoc->getModifications(4878));
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
            return response()->json([
                'response' => $this->tecdoc->getModels($request->brand_id,$request->year_auto)
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
