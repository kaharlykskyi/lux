<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\Services\Catalog;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    protected $service;

    protected $tecdoc;

    protected $pre_products = 12;

    protected $brands = [];

    protected $attribute = [];

    protected $filter_supplier;

    protected $min_price;

    protected $max_price;

    protected $catalog_products = [];

    protected $list_product = null;

    protected $list_catalog = null;

    protected $replace_product = null;

    protected $query_filters = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
        $this->service = new Catalog();
        $this->max_price = (object)[
            'start_price' => 0,
            'filter_price' => 0
        ];
        $this->min_price = (object)[
            'start_price' => 0,
            'filter_price' => 0
        ];
    }

    public function index(Request $request){
        $this->filter_supplier = isset($request->supplier)?explode(',',$request->supplier):[];

        if(session('pre_products')){
            $this->pre_products = session('pre_products');
        }

        if (isset($request->min)){
            $this->min_price->filter_price = round($request->min,2);
        }

        if (isset($request->max)){
            $this->max_price->filter_price = round($request->max,2);
        }

        $save_filters = Cache::remember('save_filters', 60, function(){
            return  DB::table('filter_settings')->where('use','=',1)->get();
        });

        $this->query_filters = $this->service->getQueryFilters($request->query());

        switch ($request){
            case isset($request->search_str):
                $this->getSearchResult($request);
                break;
            case isset($request->category):
                $type = (isset($request->type_auto)?$request->type_auto:'passenger');
                $this->tecdoc->setType($type);
                switch ($request){
                    case isset($request->modification_auto):

                        $this->brands = $this->service->getBrands('modification',['nodeid' => $request->category,'linkageid' => $request->modification_auto,'type' => $type]);

                        $price = $this->service->getMinMaxPrice('modification',['nodeid' => $request->category,'linkageid' => $request->modification_auto,'type' => $type]);
                        $this->min_price->start_price = round($price->min,2);
                        $this->max_price->start_price = round($price->max,2);

                        $this->attribute = $this->service->getAttributes('modification',['nodeid' => $request->category,'linkageid' => $request->modification_auto,'type' => $type],$save_filters);

                        $this->catalog_products = $this->tecdoc->getSectionParts($request->modification_auto,$request->category,$this->pre_products,[
                            'price' => [
                                'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                                'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$this->filter_supplier:null
                        ],$save_filters,$this->query_filters);

                        break;
                    default:

                        $rubric_category = AllCategoryTree::where('hurl',$request->category)->first();
                        if (isset($rubric_category)){
                            $request->category = $rubric_category->tecdoc_id;
                        }

                        $this->brands = $this->service->getBrands('category',[
                            'id' => $request->category,
                            'type' => $type
                        ]);

                        $price = $this->service->getMinMaxPrice('category',['id' => $request->category,'type' => $type]);
                        $this->min_price->start_price = round($price->min,2);
                        $this->max_price->start_price = round($price->max,2);

                        $this->attribute = $this->service->getAttributes('category',['id' => $request->category,'type' => $type],$save_filters);

                        $this->catalog_products = $this->tecdoc->getCategoryProduct($request->category,$this->pre_products,[
                            'price' => [
                                'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                                'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$this->filter_supplier:null
                        ],$save_filters,$this->query_filters);
                }
                break;
            case isset($request->pcode) || !empty($request->query('query')):
                $OENbr = isset($request->pcode)?$request->pcode:$request->query('query');

                $manufacturer = $this->tecdoc->getManufacturerForOed($OENbr,isset($request->trademark)?$request->trademark:null,$this->alias_manufactures);

                if (isset($manufacturer)){
                    $this->brands = $this->service->getBrands('pcode',['OENbr' =>$OENbr,'manufacturer' => $manufacturer->id]);

                    $price = $this->service->getMinMaxPrice('pcode',['OENbr' =>$OENbr,'manufacturer' => $manufacturer->id]);
                    $this->min_price->start_price = round($price->min,2);
                    $this->max_price->start_price = round($price->max,2);

                    $this->catalog_products = $this->tecdoc->getProductForArticleOE($OENbr,$manufacturer->id,$this->pre_products,[
                        'price' => [
                            'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                            'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                        ],
                        'supplier' => isset($request->supplier)?$this->filter_supplier:null
                    ]);
                } else {
                    $this->catalog_products = $this->arrayPaginator($this->catalog_products,$request,$this->pre_products);
                }
                break;
            default:
                return redirect()->route('home');
        }

        if (!isset($this->list_product)&& !isset($this->list_catalog))
            $this->catalog_products->withPath($request->fullUrl());

        return view('catalog.index')->with([
            'catalog_products' => $this->catalog_products,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'filter_supplier' => $this->filter_supplier,
            'brands' => $this->brands,
            'attribute' => $this->attribute,
            'list_product' => $this->list_product,
            'list_catalog' => $this->list_catalog,
            'replace_product' => $this->replace_product
        ]);
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
    }

    private function getSearchResult($request){
        if ($request->type === 'articles'){

            if ($request->has('supplier')){
                $this->list_product = $this->service->getCatalogProduct($request->search_str,$request->supplier);
                $this->replace_product = $this->service->replaceProducts($request->search_str,$request->supplier,$this->tecdoc->connection);
            }else{
                $this->list_catalog = $this->service->brandCatalog($request->search_str,$this->tecdoc->connection);
            }

        } elseif ($request->type === 'name'){
            $this->brands = $this->service->getBrands('search_str',[
                'str' => $request->search_str,
                'field' => $request->type
            ]);

            $price = $this->service->getMinMaxPrice('search_str',['str' => $request->search_str]);
            $this->min_price->start_price = round($price->min,2);
            $this->max_price->start_price = round($price->max,2);

            $this->catalog_products = $this->tecdoc->getProductForName(trim(strip_tags($request->search_str)),$this->pre_products,[
                'price' => [
                    'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                    'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                ],
                'supplier' => isset($request->supplier)?$this->filter_supplier:null
            ]);
        }
    }

}
