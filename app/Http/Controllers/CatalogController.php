<?php

namespace App\Http\Controllers;

use App\Product;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    protected $tecdoc;

    protected $pre_products = 12;

    protected $tecdoc_article = [];

    protected $filter = null;

    protected $filter_whereIN = null;

    protected $brands = null;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $products = null;

        if(session('pre_products')){
            $this->pre_products = session('pre_products');
        }

        if (isset($request->search_product_article)){
            $this->filter = [
                ['articles','LIKE',"%{$request->search_product_article}%"]
            ];
            $products = $this->getProduct();

        }elseif (isset($request->category)){
            if (isset($request->modification_auto) && isset($request->type_auto)){
                $this->tecdoc->setType($request->type_auto);

                $subCategory = $this->tecdoc->getSections($request->modification_auto,$request->category);
                foreach ($subCategory as $item){
                    $buff = $this->tecdoc->getSections($request->modification_auto,$item->id);
                    if (isset($buff[0])){
                        foreach ($buff as $item){
                            array_push($subCategory,$item);
                        }
                    }
                }
                dump($subCategory);
                foreach ($subCategory as $item){
                    $buff = $this->tecdoc->getSectionParts($request->modification_auto,$item->id);
                    if(isset($buff[0])){
                        foreach ($buff as $value){
                            array_push($this->tecdoc_article,$value);
                        }
                    }

                }
                dump($this->tecdoc_article);
                die();
                $this->filter_whereIN = ['articles',$this->tecdoc_article];
                $products = $this->getProduct();
            } else{
                $this->tecdoc->setType($request->type);
                $products = $this->tecdoc->getCategoryProduct($request->category);
            }

        }elseif (isset($request->trademark) && isset($request->pcode)){
            $manufactorer = $this->tecdoc->getManufacturer(trim($request->trademark));
            if (isset($manufactorer[0])){
                $article_oe = $this->tecdoc->getManufacturerForOed($request->pcode,$manufactorer[0]->id);
                $products = [];
                if (isset($article_oe[0])){
                    foreach ($article_oe as $item){
                        foreach ($this->tecdoc->getProductForArticleOE($item->datasupplierarticlenumber,$item->supplierid) as $value){
                            array_push($products,$value);
                        }
                    }
                }
            }
        } else {
            return redirect()->route('home');
        }

        $brands = $this->brands;
        $products = $this->arrayPaginator($products, $request,$this->pre_products );
        $products->withPath($request->fullUrl());
        return view('catalog.index',compact('products','brands'));
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
    }

    protected function getProduct(){
        $products = $this->tecdoc->getProduct();

        return $products;
    }
}
