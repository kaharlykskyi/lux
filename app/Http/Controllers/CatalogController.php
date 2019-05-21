<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\Product;
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

    protected $filter_supplier;

    protected $min_price;

    protected $max_price;

    protected $catalog_products = [];

    protected $list_product = null;

    protected $list_catalog = null;

    protected $replace_product = null;

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

        //$save_filetrs = DB::table('filter_settings')->where('use','=',1)->get();

        switch ($request){
            case isset($request->search_str):
                $this->getSearchResult($request);
                break;
            case isset($request->category):
                $type = (isset($request->type_auto)?$request->type_auto:'passenger');
                $this->tecdoc->setType($type);
                switch ($request){
                    case isset($request->modification_auto):
                        $this->catalog_products = $this->tecdoc->getSectionParts($request->modification_auto,$request->category,$this->pre_products);
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

                        $this->catalog_products = $this->tecdoc->getCategoryProduct($request->category,$this->pre_products,[
                            'price' => [
                                'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                                'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                            ],
                            'supplier' => isset($request->supplier)?$this->filter_supplier:null
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
                    $this->min_price->start_price = round($price->min,2);
                    $this->max_price->start_price = round($price->max,2);

                    $this->catalog_products = $this->tecdoc->getProductForArticleOE($request->pcode,$manufacturer[0]->id,$this->pre_products,[
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
                $list_product_loc = Product::with('provider')
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_numbers'),'article_numbers.DataSupplierArticleNumber','=','products.articles')
                    ->where('article_numbers.SupplierId',(int)$request->supplier)
                    ->where('article_numbers.DataSupplierArticleNumber','LIKE',"%$request->search_str%")
                    ->select('products.*','article_numbers.SupplierId AS supplierId')
                    ->orderBy('products.price')
                    ->get();

                $buff_is = [];
                $this->list_product = [];
                foreach ($list_product_loc as $item){
                    foreach ($list_product_loc as $val){
                        if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                            $buff_is[] = $val->id;
                            $this->list_product[$item->articles][] = $val;
                        }
                    }
                }

                $this->list_product = $this->arrayPaginator($this->list_product,$request,$this->pre_products);

            }else{
                $this->getProduct([
                    ['products.articles','=',$request->search_str]
                ]);

                if (count($this->list_product) === 1){
                    $this->list_product = $this->arrayPaginator($this->list_product,$request,$this->pre_products);
                } else{
                    $this->list_catalog = DB::connection($this->tecdoc->connection)
                        ->table('suppliers')
                        ->join('article_numbers','article_numbers.SupplierId','=','suppliers.id')
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                            $query->on('p.articles','=','article_numbers.DataSupplierArticleNumber');
                            $query->on('p.brand','=','suppliers.matchcode');
                        })
                        ->where('article_numbers.DataSupplierArticleNumber','LIKE',"%$request->search_str%")
                        ->select('suppliers.id AS SupplierId','suppliers.matchcode',
                            DB::raw('(SELECT p2.name FROM '.config('database.connections.mysql.database').
                                '.products AS p2 WHERE p2.articles LIKE "%'.$request->search_str.'%" AND p2.brand = suppliers.matchcode LIMIT 1) as product_name'),
                            DB::raw('COUNT(p.articles) AS count'))
                        ->groupBy('suppliers.id','suppliers.matchcode','product_name')
                        ->distinct()
                        ->get();
                }
            }

        } elseif ($request->type === 'name'){
            $this->brands = $this->service->getBrands('search_str',[
                'str' => $request->search_str,
                'field' => $request->type
            ]);

            $price = $this->service->getMinMaxPrice('search_str',['str' => $request->search_str]);
            $this->min_price->start_price = round($price->min,2);
            $this->max_price->start_price = round($price->max,2);

            //$this->attribute = $this->service->getAttributes('search_str',['str' => $request->search_str],$save_filetrs);

            $this->catalog_products = $this->tecdoc->getProductForName(trim(strip_tags($request->search_str)),$this->pre_products,[
                'price' => [
                    'min' => ($this->min_price->filter_price > 0)?$this->min_price->filter_price:$this->min_price->start_price,
                    'max' => ($this->max_price->filter_price > 0)?$this->max_price->filter_price:$this->max_price->start_price
                ],
                'supplier' => isset($request->supplier)?$this->filter_supplier:null
            ]);
        }
    }

    protected function getProduct($filter = []){
        $list_product_loc = Product::with('provider')
            ->where($filter)
            ->select('products.*',DB::raw("(SELECT s.id FROM " . config('database.connections.mysql_tecdoc.database').".suppliers AS s WHERE s.matchcode=products.brand) AS supplierId"))
            ->orderBy('products.price')
            ->get();

        $buff_is = [];
        $this->list_product = [];
        foreach ($list_product_loc as $item){
            foreach ($list_product_loc as $val){
                if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                    $buff_is[] = $val->id;
                    $this->list_product[$item->articles][] = $val;
                }
            }
        }
    }

    protected function replaceProducts(Request $request){
        $filter = [];

        if (isset($request->article) && !empty($request->article)) $filter[] = ['article_oe.DataSupplierArticleNumber','=',$request->article];
        if (isset($request->supplierId) && !empty($request->supplierId)) $filter[] = ['article_oe.SupplierId','=',(int)$request->supplierId];

        $replace_product_loc = DB::connection($this->tecdoc->connection)
            ->table('article_oe')
            ->join('article_cross',function ($query){
                $query->on('article_cross.OENbr','=','article_oe.OENbr');
                $query->on('article_cross.manufacturerId','=','article_oe.manufacturerId');
            })
            ->join('article_links',function ($query){
                $query->on('article_links.SupplierId','=','article_cross.SupplierId');
                $query->on('article_links.DataSupplierArticleNumber','=','article_cross.PartsDataSupplierArticleNumber');
            })
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),'p.articles','=','article_cross.PartsDataSupplierArticleNumber')
            ->where($filter)
            ->where('p.articles','<>',$request->article)
            ->select('p.*','article_cross.SupplierId')
            ->distinct()
            ->get();

        $buff_is = [];
        $this->replace_product = [];
        foreach ($replace_product_loc as $item){
            foreach ($replace_product_loc as $val){
                if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                    $buff_is[] = $val->id;
                    $this->replace_product[$item->articles][] = $val;
                }
            }
        }
        return view('catalog.replace_products')->with([
            'replace_product' => $this->replace_product
        ]);
    }
}
