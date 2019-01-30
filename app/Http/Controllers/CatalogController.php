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

    protected $brands = [];

    protected $attribute = [];

    protected $in_stock = [];

    protected $not_stock = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $products = [];
        $min_price = 0;
        $max_price = 0;

        if(session('pre_products')){
            $this->pre_products = session('pre_products');
        }

        if (isset($request->search_product_article)){
            $products = $this->tecdoc->getProductForArticle(str_replace([' ','.','/'],'',trim(strip_tags($request->search_product_article))));

        }elseif (isset($request->brand)){
            $products = DB::connection('mysql_tecdoc')->select("SELECT DISTINCT a.supplierId,a.DataSupplierArticleNumber,a.NormalizedDescription,s.matchcode FROM `article_cross` AS ac 
                                                                             JOIN `suppliers` s ON s.id=ac.SupplierId
                                                                             JOIN `articles` as a ON a.supplierId=ac.SupplierId AND a.DataSupplierArticleNumber=ac.PartsDataSupplierArticleNumber
                                                                             WHERE ac.manufacturerId={$request->brand} AND a.HasPassengerCar=TRUE LIMIT 5000");

        }elseif (isset($request->model)){
            $products = DB::connection('mysql_tecdoc')->select("SELECT a.supplierId,a.DataSupplierArticleNumber,a.NormalizedDescription,s.matchcode FROM `passanger_cars` AS pc
                                                                             JOIN `passanger_car_pds` AS pcp ON pcp.passangercarid=pc.id
                                                                             JOIN `article_links` AS al ON al.productid=pcp.productid
                                                                             JOIN `articles` AS a ON a.DataSupplierArticleNumber=al.DataSupplierArticleNumber
                                                                             JOIN `suppliers` s ON s.id=pcp.supplierid
                                                                             WHERE pc.modelid={$request->model} AND a.HasPassengerCar=TRUE LIMIT 5000");
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
                foreach ($subCategory as $item){
                    $buff = $this->tecdoc->getSectionParts($request->modification_auto,$item->id);
                    if(isset($buff[0])){
                        foreach ($buff as $value){
                            array_push($products,$value);
                        }
                    }
                }

            } else{
                $this->tecdoc->setType($request->type);
                $products = $this->tecdoc->getCategoryProduct($request->category);
                foreach ($products as $k => $product){
                    $data = $this->tecdoc->getArtStatus($product->DataSupplierArticleNumber,$product->supplierId);
                    if(isset($data[0])){
                        $products[$k]->NormalizedDescription = $data[0]->NormalizedDescription;
                    }else{
                        unset($products[$k]);
                    }
                }
            }

        }elseif (isset($request->trademark) && isset($request->pcode)){
            $manufactorer = $this->tecdoc->getManufacturer(trim($request->trademark));
            if (isset($manufactorer[0])){
                $article_oe = $this->tecdoc->getManufacturerForOed($request->pcode,$manufactorer[0]->id);
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

        foreach ($products as $product){
            $catalog_product = Product::where('articles',str_replace(' ','',$product->DataSupplierArticleNumber))->first();
            $flag_add_brand = true;
            $attr = $this->tecdoc->getArtAttributes($product->DataSupplierArticleNumber,$product->supplierId);
            $product->filter_data = $attr;
            foreach ($attr as $item){
                $this->attribute[$item->id][] = $item;
            }

            foreach ($this->brands as $brand){
                if ($brand->supplierId === $product->supplierId){
                    $flag_add_brand = false;
                }
            }
            if ($flag_add_brand){
                $this->brands[] = (object)[
                    'supplierId' => $product->supplierId,
                    'description' => $product->matchcode
                ];
            }

            if (isset($catalog_product)){
                $product->price = $catalog_product->price;
                if ($product->price < $min_price || $min_price === 0){
                    $min_price = $product->price;
                }
                if ($product->price > $max_price || $max_price === 0){
                    $max_price = $product->price;
                }
                $product->id = $catalog_product->id;
                $product->name = $catalog_product->name;
                $this->in_stock[] = $product;
            }else{
                $this->not_stock[] = $product;
            }
        }

        $attribute = $this->attribute;
        $brands = $this->brands;
        $products = $this->arrayPaginator($this->filterProduct(), $request,$this->pre_products );
        $products->withPath($request->fullUrl());
        return view('catalog.index',compact('products','brands','min_price','max_price','attribute'));
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
        if (isset($request->min_price) && isset($request->max_price)){
            session()->forget('filter.min_price');
            session()->forget('filter.max_price');
            session(['filter' => [
                'min_price' => (float)str_replace('₴','',$request->min_price),
                'max_price' => (float)str_replace('₴','',$request->max_price)
            ]]);
        }
        if (isset($request->clear_filter)){
            session()->forget('filter');
        }
        if (isset($request->supplierid) && isset($request->active)){
            if ($request->active === 'true'){
                session()->put("filter.suppliers.{$request->supplierid}",$request->supplierid);
            } else {
                session()->forget("filter.suppliers.{$request->supplierid}");
                if (empty(session('filter.suppliers'))){
                    session()->forget('filter.suppliers');
                }
            }
        }
        if(isset($request->attrFilter) && isset($request->active)){
            $data = explode('@',$request->attrFilter,2);
            if ($request->active === 'true'){
                if (session()->has("filter.attributes.{$data[0]}")){
                    $buff = session("filter.attributes.{$data[0]}");
                    array_push($buff,$data[1]);
                    session()->put("filter.attributes.{$data[0]}",$buff);
                }else{
                    session()->put("filter.attributes.{$data[0]}",[$data[1]]);
                }
            } else {
                $buff = session("filter.attributes.{$data[0]}");
                foreach ($buff as $k => $item){
                    if($item === $data[1]){
                        unset($buff[$k]);
                    }
                }
                if (empty($buff)){
                    session()->forget("filter.attributes.{$data[0]}");
                }else{
                    session()->put("filter.attributes.{$data[0]}",$buff);
                }
                if (empty(session('filter.attributes'))){
                    session()->forget('filter.attributes');
                }
            }
        }
    }

    protected function filterProduct(){
        if(!session()->has('filter.min_price') && !session()->has('filter.max_price')){
            $products = array_merge($this->in_stock,$this->not_stock);
        } else {
            $products = $this->in_stock;
        }

        foreach ($products as $k => $product){
            if (session()->has('filter.min_price') && session()->has('filter.max_price')){
                if ($product->price < session('filter.min_price') && $product->price > session('filter.max_price')){
                    unset($products[$k]);
                }
            }
            if (session()->has('filter.suppliers') && !empty(session('filter.suppliers'))){
                foreach (session('filter.suppliers') as $supplier){
                    if ((int)$supplier === $product->supplierId){
                        break;
                    }
                    unset($products[$k]);
                }
            }
            if (session()->has('filter.attributes') && !empty(session('filter.attributes'))){
                $delete = true;
                foreach (session('filter.attributes') as $kay => $attributes){
                    foreach ($attributes as $attribute){
                        foreach ($product->filter_data as $attr_prod){
                            if ($attr_prod->id === $kay && $attribute === $attr_prod->displayvalue){
                                $delete = false;
                            }
                        }
                    }
                }
                if ($delete){
                    unset($products[$k]);
                }
            }
        }

        return $products;
    }
}
