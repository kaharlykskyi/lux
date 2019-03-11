<?php

namespace App\Http\Controllers;

use App\Services\Catalog;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $service;

    protected $tecdoc;

    protected $pre_products = 12;

    protected $brands = [];

    protected $attribute = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
        $this->service = new Catalog();
    }

    public function index(Request $request){
        $catalog_products = [];
        $min_price = (object)[
            'start_price' => 0,
            'filter_price' => 0
        ];
        $max_price = (object)[
            'start_price' => 0,
            'filter_price' => 0
        ];

        if(session('pre_products')){
            $this->pre_products = session('pre_products');
        }

        if (isset($request->min)){
            $min_price->filter_price = round($request->min,2);
        }

        if (isset($request->max)){
            $max_price->filter_price = round($request->max,2);
        }

        switch ($request){
            case isset($request->search_str):
                $min_price->start_price = $this->service->getMinPrice('search_str',['str' => $request->search_str]);
                $max_price->start_price = $this->service->getMaxPrice('search_str',['str' => $request->search_str]);
                $catalog_products = $this->tecdoc->getProductForArticle(trim(strip_tags($request->search_str)),$this->pre_products,[
                    'price' => [
                        'min' => ($min_price->filter_price > 0)?$min_price->filter_price:$min_price->start_price,
                        'max' => ($max_price->filter_price > 0)?$max_price->filter_price:$max_price->start_price
                    ]
                ]);
                break;
            case isset($request->category):
                $this->tecdoc->setType((isset($request->type_auto)?$request->type_auto:'passenger'));
                switch ($request){
                    case isset($request->modification_auto):
                        $catalog_products = $this->tecdoc->getSectionParts($request->modification_auto,$request->category,$this->pre_products);
                        break;
                    case isset($request->model):
                        $catalog_products = $this->tecdoc->getProductByModelCategory($request->model,$this->pre_products,$request->category);
                        break;
                    default:
                        $catalog_products = $this->tecdoc->getCategoryProduct($request->category,$this->pre_products);
                }
                break;
            case (isset($request->trademark) && isset($request->pcode)):
                $manufactorer = $this->tecdoc->getManufacturer(trim($request->trademark));
                if (isset($manufactorer[0])){
                    $article_oe = $this->tecdoc->getManufacturerForOed($request->pcode,$manufactorer[0]->id);
                    if (isset($article_oe[0])){
                        $datasupplierarticlenumber = [];
                        $supplierid = [];
                        foreach ($article_oe as $item){
                            $datasupplierarticlenumber[] = $item->datasupplierarticlenumber;
                            $supplierid = $item->supplierid;
                        }

                        $catalog_products = $this->tecdoc->getProductForArticleOE($datasupplierarticlenumber,$supplierid,$this->pre_products);
                    }
                }
                break;
            default:
                return redirect()->route('home');
        }

        $attribute = /*$this->attribute*/null;
        $brands = /*$this->brands*/null;

        $catalog_products->withPath($request->fullUrl());

        return view('catalog.index',compact('catalog_products','brands','min_price','max_price','attribute'));
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
    }

}
