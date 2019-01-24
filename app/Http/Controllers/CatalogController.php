<?php

namespace App\Http\Controllers;

use App\Product;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $tecdoc;

    protected $pre_products = 12;

    protected $filter = null;

    protected $filter_whereIN = null;

    protected $brands = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $products = [];
        $min_price = 0;
        $max_price = 0;
        $attribute = [];

        if(session('pre_products')){
            $this->pre_products = session('pre_products');
        }

        if (isset($request->search_product_article)){
            $products = $this->tecdoc->getProductForArticle(str_replace([' ','.','/'],'',trim(strip_tags($request->search_product_article))));
            foreach ($products as $product){
                $file = $this->tecdoc->getArtFiles($product->DataSupplierArticleNumber,$product->supplierId);
                $product->PictureName = isset($file[0])?$file[0]->PictureName:null;
                $product->PictureDescription = isset($file[0])?$file[0]->Description:null;
            }

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
                            $file = $this->tecdoc->getArtFiles($value->DataSupplierArticleNumber,$value->supplierId);
                            $value->PictureName = isset($file[0])?$file[0]->PictureName:null;
                            $value->PictureDescription = isset($file[0])?$file[0]->Description:null;
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
                        $file = $this->tecdoc->getArtFiles($product->DataSupplierArticleNumber,$product->supplierId);
                        $products[$k]->Description = isset($file[0])?$file[0]->Description:null;
                        $products[$k]->PictureName = isset($file[0])?$file[0]->PictureName:null;
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


        $in_stock = [];
        $not_stock = [];
        foreach ($products as $product){
            $attr = $this->tecdoc->getArtAttributes($product->DataSupplierArticleNumber,$product->supplierId);
            foreach ($attr as $item){
                $attribute[$item->id][] = $item;
            }
            $flag_add_brand = true;
            $catalog_product = Product::where('articles',str_replace(' ','',$product->DataSupplierArticleNumber))->first();
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
                $in_stock[] = $product;
            }else{
                $not_stock[] = $product;
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
        }
        $brands = $this->brands;
        $products = $this->arrayPaginator(array_merge($in_stock,$not_stock), $request,$this->pre_products );
        $products->withPath($request->fullUrl());
        return view('catalog.index',compact('products','brands','min_price','max_price','attribute'));
    }

    public function filter(Request $request){
        if (isset($request->pre_show)){
            session()->forget('pre_products');
            session(['pre_products' => (int)$request->pre_show]);
        }
    }
}
