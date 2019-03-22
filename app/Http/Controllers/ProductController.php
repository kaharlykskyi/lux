<?php

namespace App\Http\Controllers;

use App\{Product, TecDoc\Tecdoc};
use Illuminate\{Http\Request, Support\Facades\Auth, Support\Facades\DB};
use App\Services\Product as ProductService;

class ProductController extends Controller
{

    protected $tecdoc;

    protected $service;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
        $this->service = new ProductService();
    }

    public function index(Request $request){
        $request->alias = str_replace('@','/',$request->alias);
        if(!isset($request->supplierid)){
            $duff = DB::connection('mysql_tecdoc')->select("SELECT supplierId FROM articles WHERE DataSupplierArticleNumber='{$request->alias}' OR FoundString='{$request->alias}'");
            if (isset($duff[0])){
                $request->supplierid = $duff[0]->supplierId;
            }else{
                $request->supplierid = null;
            }
        }

        if (isset($request->supplierid)){
            $art_replace = $this->tecdoc->getArtReplace($request->alias,$request->supplierid);
            $accessories = $this->tecdoc->getAccessories($request->alias);
            $product_vehicles = $this->tecdoc->getArtVehicles($request->alias,$request->supplierid);
            $product_attr = $this->tecdoc->getArtAttributes($request->alias,$request->supplierid);
            $files = $this->tecdoc->getArtFiles($request->alias,$request->supplierid);
            $supplier_details = $this->tecdoc->getSupplieInfo($request->supplierid);
        }

        $product = Product::with('comment')->where('articles', $request->alias)->first();

        return view('product.product_detail',compact('product','product_attr','product_vehicles','files','accessories','art_replace','supplier_details'));
    }

    public function fastBuy(Request $request){
        return response()->json($this->service->setFastBuy([
            'product_id' => $request->id,
            'phone' => $request->phone
        ]));
    }

    public function addCart(Request $request){
        return response()->json($this->service->addToCart($request,Auth::id()));
    }

    public function writeComment(Request $request){
        return response()->json($this->service->setProductComment($request->except('_token'),Auth::id(),Auth::user()->name));
    }
}
