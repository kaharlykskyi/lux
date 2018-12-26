<?php

namespace App\Http\Controllers;

use App\Product;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $tecdoc;

    protected $pre_products = 12;

    protected $product_id = null;

    protected $filter = null;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        if(isset($request->pre_products)){
            $this->pre_products = $request->pre_products;
        }

        if (isset($request->trademark)){
            $this->filter[] = ['brand',$request->trademark];
        }

        if (isset($this->filter)){
            $products = Product::where($this->filter)->paginate($this->pre_products);
        } else {
            $products = Product::paginate($this->pre_products);
        }

        $products->withPath($request->fullUrl());
        foreach ($products as $product){
            $this->product_id[] = $product->articles;
        }
        //$products_img = $this->tecdoc->getArtFilesForArticles($this->product_id);
        dump($this->tecdoc->getPrd());
        $brands = null/*$this->tecdoc->getBrands()*/;


        return view('catalog.index',compact('products','brands'));
    }
}
