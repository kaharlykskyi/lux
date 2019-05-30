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

        $product = Product::with('comment')->findOrFail($request->product_id);
        $accessories = $this->tecdoc->getAccessories($product->articles);

        if(!isset($request->supplierid)){
            $request->supplierid = $this->getSupplier($product->articles);
        }

        if (isset($request->supplierid)){
            $art_replace = $this->tecdoc->getArtCross($product->articles,$request->supplierid);
            $product_vehicles = $this->tecdoc->getArtVehicles($product->articles,$request->supplierid);
            $product_attr = $this->tecdoc->getArtAttributes($product->articles,$request->supplierid);
            $files = $this->tecdoc->getArtFiles($product->articles,$request->supplierid);
            $supplier_details = $this->tecdoc->getSupplieInfo($request->supplierid);
        }

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

    public function fullInfoProduct(Request $request){
        if(!isset($request->supplier)){
            $request->supplier = $this->getSupplier($request->article);
        }

        $product = Product::with('comment')->where('articles', $request->article)->first();

        if (isset($request->supplier)){
            $product_vehicles = $this->tecdoc->getArtVehicles($request->article,$request->supplier);
            $product_attr = $this->tecdoc->getArtAttributes($request->article,$request->supplier);
            $files = $this->tecdoc->getArtFiles($request->article,$request->supplier);
        }

        return response()->json([
            'product' => $product,
            'file' => isset($files[0])?$files[0]:null,
            'sup' => $request->supplier,
            'attr' => !empty($product_attr)?$product_attr:null,
            'vehicles' => !empty($product_vehicles)?$product_vehicles:null
        ]);
    }

    protected function getSupplier($article){
        $duff = DB::connection('mysql_tecdoc')->select("SELECT supplierId FROM articles WHERE DataSupplierArticleNumber='{$article}'");
        if (isset($duff[0])){
            return $duff[0]->supplierId;
        }else{
            return null;
        }
    }
}
