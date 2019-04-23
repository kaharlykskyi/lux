<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\Services\Catalog;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $filter_supplier = isset($request->supplier)?explode(',',$request->supplier):[];
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
                $this->brands = $this->service->getBrands('search_str',[
                    'str' => $request->search_str
                ]);

                $price = $this->service->getMinMaxPrice('search_str',['str' => $request->search_str]);
                $min_price->start_price = round($price->min,2);
                $max_price->start_price = round($price->max,2);

                //$this->attribute = $this->service->getAttributes('search_str',['str' => $request->search_str]);
                //dd($this->attribute);
                $catalog_products = $this->tecdoc->getProductForArticle(trim(strip_tags($request->search_str)),$this->pre_products,[
                    'price' => [
                        'min' => ($min_price->filter_price > 0)?$min_price->filter_price:$min_price->start_price,
                        'max' => ($max_price->filter_price > 0)?$max_price->filter_price:$max_price->start_price
                    ],
                    'supplier' => isset($request->supplier)?$filter_supplier:null
                ]);
                break;
            case isset($request->category):
                $type = (isset($request->type_auto)?$request->type_auto:'passenger');
                $this->tecdoc->setType($type);
                switch ($request){
                    case isset($request->modification_auto):
                        $catalog_products = $this->tecdoc->getSectionParts($request->modification_auto,$request->category,$this->pre_products);
                        break;
                    default:

                        $rubric_category = AllCategoryTree::where('hurl',$request->category)->first();
                        if (isset($rubric_category)){
                            $category = DB::connection('mysql_tecdoc')
                                ->table('prd')
                                ->orWhere('normalizeddescription',$rubric_category->tecdoc_name)
                                ->orWhere('usagedescription',$rubric_category->tecdoc_name)
                                ->first();
                            if (isset($category)){
                                $request->category = $category->id;
                            }
                        }

                        $this->brands = $this->service->getBrands('category',[
                            'id' => $request->category,
                            'type' => $type
                        ]);

                        $price = $this->service->getMinMaxPrice('category',['id' => $request->category,'type' => $type]);
                        $min_price->start_price = round($price->min,2);
                        $max_price->start_price = round($price->max,2);

                        $catalog_products = $this->tecdoc->getCategoryProduct($request->category,$this->pre_products,[
                            'price' => [
                                'min' => ($min_price->filter_price > 0)?$min_price->filter_price:$min_price->start_price,
                                'max' => ($max_price->filter_price > 0)?$max_price->filter_price:$max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$filter_supplier:null
                        ]);
                }
                break;
            case isset($request->pcode):
                if (!isset($request->trademark)){
                    $manufacturer = $this->tecdoc->getManufacturerForOed($request->pcode);
                } else{
                    $manufacturer = $this->tecdoc->getManufacturer(trim($request->trademark));
                }

                if (isset($manufacturer[0])){
                    $this->brands = $this->service->getBrands('pcode',['OENbr' =>$request->pcode,'manufacturer' => $manufacturer[0]->id]);

                    $price = $this->service->getMinMaxPrice('pcode',['OENbr' =>$request->pcode,'manufacturer' => $manufacturer[0]->id]);
                    $min_price->start_price = round($price->min,2);
                    $max_price->start_price = round($price->max,2);

                    $catalog_products = $this->tecdoc->getProductForArticleOE($request->pcode,$manufacturer[0]->id,$this->pre_products,[
                        'price' => [
                            'min' => ($min_price->filter_price > 0)?$min_price->filter_price:$min_price->start_price,
                            'max' => ($max_price->filter_price > 0)?$max_price->filter_price:$max_price->start_price
                        ],
                        'supplier' => isset($request->supplier)?$filter_supplier:null
                    ]);
                } else {
                    $catalog_products = $this->arrayPaginator($catalog_products,$request,$this->pre_products);
                }
                break;
            default:
                return redirect()->route('home');
        }

        $attribute = $this->attribute;
        $brands = $this->brands;

        $catalog_products->withPath($request->fullUrl());

        return view('catalog.index',compact('catalog_products','brands','min_price','max_price','attribute','filter_supplier'));
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
    }

}
