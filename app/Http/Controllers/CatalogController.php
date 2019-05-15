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
                    ->select('products.*')
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

                if (count($this->list_product) === 1){
                    $product = $list_product_loc[0];
                    $replace_product_loc = DB::connection($this->tecdoc->connection)
                        ->table('article_rn')
                        ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),'p.articles','=','article_rn.replacedatasupplierarticlenumber')
                        ->where('article_rn.DataSupplierArticleNumber',$product->articles)
                        ->select('p.*','article_rn.supplierid as SupplierId')
                        ->get();
                    $buff_is = [];
                    $this->replace_product = [];
                    foreach ($replace_product_loc as $item){
                        foreach ($list_product_loc as $val){
                            if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                                $buff_is[] = $val->id;
                                $this->replace_product[$item->articles][] = $val;
                            }
                        }
                    }
                }

            }else{
                $this->list_catalog = DB::connection($this->tecdoc->connection)
                    ->table('suppliers')
                    ->join('article_numbers','article_numbers.SupplierId','=','suppliers.id')
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),'p.articles','=','article_numbers.DataSupplierArticleNumber')
                    ->where('article_numbers.DataSupplierArticleNumber','LIKE',"%$request->search_str%")
                    ->select('suppliers.id AS SupplierId','suppliers.matchcode',DB::raw('COUNT(p.articles) as count'))
                    ->groupBy('suppliers.id','suppliers.matchcode')
                    ->having('count','>',0)
                    ->distinct()
                    ->get();
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
}
