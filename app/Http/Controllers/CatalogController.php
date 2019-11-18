<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\Product;
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

    protected $sort_prise = 'ASC';

    protected $brands = [];

    protected $attribute = [];

    protected $filter_supplier;

    protected $min_price;

    protected $max_price;

    protected $catalog_products = [];

    protected $list_product;

    protected $list_catalog;

    protected $replace_product;

    protected $query_filters = [];

    protected $art_file;

    public function __construct()
    {
        parent::__construct();
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
        if (session()->has('price_sort')){
            $this->sort_prise = session()->get('price_sort');
        }

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

                        $this->max_price->start_price = round(self::getMaxPrice(),2);

                        $this->attribute = $this->service->getAttributes('modification',['nodeid' => $request->category,'linkageid' => $request->modification_auto,'type' => $type],$save_filters);

                        $this->catalog_products = $this->tecdoc->getSectionParts($request->modification_auto,$request->category,$this->pre_products,[
                            'price' => [
                                'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                                'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$this->filter_supplier:null
                        ],$save_filters,$this->query_filters,$this->sort_prise);

                        break;
                    default:
                        $rubric_category = AllCategoryTree::with('subCategory')
                            ->where('hurl',$request->category)
                            ->orWhere('tecdoc_id',(int)$request->category)
                            ->firstOrFail();

                        $this->brands = $this->service->getBrands('category',[
                            'category' => $rubric_category,
                            'type' => $type,
                            'car' => $request->car
                        ]);

                        $this->max_price->start_price = round(self::getMaxPrice(),2);

                        $this->attribute = $this->service->getAttributes('category',['category' => $rubric_category,'type' => $type,'car' => $request->car],$save_filters);

                        $this->catalog_products = $this->tecdoc->getCategoryProduct($rubric_category,$request->car,$this->pre_products,[
                            'price' => [
                                'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                                'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$this->filter_supplier:null
                        ],$save_filters,$this->query_filters,$this->sort_prise);

                        $this->art_file = $this->service->getArtFile($this->catalog_products,$this->tecdoc->connection);
                }
                break;
            case isset($request->pcode) || !empty($request->query('query')):
                $OENbr = isset($request->pcode)?$request->pcode:$request->query('query');

                $manufacturer = $this->tecdoc->getManufacturerForOed([
                    'OENbr' => isset($request->trademark)?null:$OENbr,
                    'trademark' =>isset($request->trademark)?$request->trademark:null
                ],$this->alias_manufactures);

                if (isset($manufacturer)){
                    $this->brands = $this->service->getBrands('pcode',['OENbr' =>$OENbr,'manufacturer' => $manufacturer->id]);

                    $this->max_price->start_price = round(self::getMaxPrice(),2);

                    $this->catalog_products = $this->tecdoc->getProductForArticleOE($OENbr,$manufacturer->id,$this->pre_products,[
                        'price' => [
                            'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                            'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                        ],
                        'supplier' => isset($request->supplier)?$this->filter_supplier:null
                    ],$this->sort_prise);

                    $original_products = Product::join(DB::raw(config('database.connections.mysql_tecdoc.database').'.manufacturers m'),'m.id','=','products.brand')
                        ->where('products.brand',$manufacturer->id)
                        ->where('products.articles',$OENbr)
                        ->select('products.*','m.description AS matchcode')
                        ->distinct()
                        ->get();

                    $this->catalog_products = $this->catalog_products->getCollection()->toArray();
                    foreach ($original_products as $item){
                        array_push($this->catalog_products,$item);
                    }
                    $this->catalog_products = $this->arrayPaginator($this->catalog_products,$request,$this->pre_products);
                }else{
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
            'replace_product' => $this->replace_product,
            'files' => $this->art_file
        ]);
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
        if (isset($request->price_sort)){
            session()->forget('price_sort');
            session(['price_sort' => $request->price_sort]);
        }
    }

    private function getSearchResult($request){
        if ($request->type === 'articles'){

            $request->search_str = $this->replaceRUonEN($request->search_str);

            if ($request->has('supplier')){
                $this->list_product = $this->service->getCatalogProduct($request->search_str,$request->supplier);
                $this->replace_product = $this->service->replaceProducts($request->search_str,$request->supplier,$this->tecdoc->connection);
            }elseif ($request->has('manufacturer')){
                $this->list_product = $this->service->getProductForOENbr($request->search_str);
                $this->replace_product = $this->service->getReplaceProductForOENrb($request->search_str,$request->manufacturer,$this->tecdoc->connection);
            }else{
                $this->list_catalog = $this->service->brandCatalog($request->search_str,$this->tecdoc->connection);

                if ($this->list_catalog->count() === 0){
                    $this->list_catalog = $this->service->getManufacturerForOENbr($request->search_str,$this->tecdoc->connection);
                }

                if ($this->list_catalog->count() === 0){
                    $this->list_product = $this->service->getProductForOENbr($request->search_str);
                }
            }

        } elseif ($request->type === 'name'){
            $this->brands = $this->service->getBrands('search_str',[
                'str' => $request->search_str,
                'field' => $request->type
            ]);

            $this->max_price->start_price = round(self::getMaxPrice(),2);

            $this->catalog_products = $this->tecdoc->getProductForName(trim(strip_tags($request->search_str)),$this->pre_products,[
                'price' => [
                    'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                    'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                ],
                'supplier' => isset($request->supplier)?$this->filter_supplier:null
            ],$this->sort_prise);
        }
    }

    private function getMaxPrice(){
        $price = 0;
        foreach ($this->brands as $item){
            if ($price < $item->max){
                $price = $item->max;
            }
        }

        return $price;
    }

}
