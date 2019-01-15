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

    protected $tecdoc_article = [];

    protected $filter = null;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $brands = null;

        if (isset($request->search_product_article)){
            $products = Product::where('articles','LIKE',"%{$request->search_product_article}%")->paginate($this->pre_products);
            $products->withPath($request->fullUrl());
            return view('catalog.index',compact('products','brands'));
        }

        if(isset($request->pre_products)){
            $this->pre_products = $request->pre_products;
        }

        if (isset($request->category)){
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

                foreach ($subCategory as $item){
                    $buff = $this->tecdoc->getSectionParts($request->modification_auto,$item->id);
                    if(isset($buff[0])){
                        foreach ($buff as $value){
                            array_push($this->tecdoc_article,$value->part_number);
                        }
                    }
                }

                $products = Product::whereIn('articles',$this->tecdoc_article)->paginate($this->pre_products);
                $products->withPath($request->fullUrl());
                return view('catalog.index',compact('products','brands'));
            }

            $products = Product::where('alias','LIKE',"%{$request->category}%")->paginate($this->pre_products);
            $products->withPath($request->fullUrl());
            return view('catalog.index',compact('products','brands'));
        }

        if (isset($request->trademark) && isset($request->pcode)){
            $manufactorer = $this->tecdoc->getManufacturer(trim($request->trademark));
            $article_oe = $this->tecdoc->getManufacturerForOed(trim($request->pcode),$manufactorer[0]->id);
            $article = [];
            if (count($article_oe) > 0){
                foreach ($article_oe as $k => $item){
                    $article[] = $item->datasupplierarticlenumber;
                }
            }
            $products = Product::whereIn('articles',$article)->paginate($this->pre_products);
            $products->withPath($request->fullUrl());
            return view('catalog.index',compact('products','brands'));
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

        return view('catalog.index',compact('products','brands'));
    }
}
