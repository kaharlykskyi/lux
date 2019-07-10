<?php

namespace App\Http\Controllers\Admin;

use App\AliasBrand;
use App\NoBrandProduct;
use App\Product;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NoBrandProductController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(){
        $no_brands = NoBrandProduct::select('brand',DB::raw('COUNT(id) AS count_product'))
            ->groupBy('brand')
            ->paginate(40);
        $suppliers = $this->tecdoc->getAllSuppliers();
        return view('admin.no_brand.products',compact('no_brands','suppliers'));
    }

    public function createReplace(Request $request){
        $supplier_data = explode('#',$request->suppliers_tecdoc);
        $products = NoBrandProduct::where('brand','=',$request->alias_brand)->get();

        $alias_brand = new AliasBrand();
        $alias_brand->fill([
            'name' => $request->alias_brand,
            'tecdoc_name' => $supplier_data[0]
        ]);

        if ($alias_brand->save()){
            foreach ($products as $product){
                $new_product = new Product();
                $product->brand = (int)$supplier_data[1];
                $new_product->fill($product->getAttributes());
                if ($new_product->save()){
                    NoBrandProduct::where('id',$product->id)->delete();
                }
            }
        }

        return back();
    }

    public function createBrand(Request $request){
        $products = NoBrandProduct::where('brand','=',$request->brand_name)->get();

        $new_brand_id = DB::connection($this->tecdoc->connection)->table('suppliers')->latest('id')->first(['id']);

        DB::connection($this->tecdoc->connection)->table('suppliers')
            ->insert([
                'id' => $new_brand_id->id + 1,
                'description' => $request->brand_name,
                'matchcode' => $request->brand_name
            ]);

        if ($new_brand_id->id){
            foreach ($products as $product){
                $new_product = new Product();
                $product->brand = (int)$new_brand_id->id + 1;
                $new_product->fill($product->getAttributes());
                if ($new_product->save()){
                    NoBrandProduct::where('id',$product->id)->delete();
                }
            }
        }

        return back();
    }

    public function brandAlias(Request $request){
        if ($request->isMethod('post')){
            AliasBrand::where('id',(int)$request->id)->update([
                'name' => $request->name,
                'tecdoc_name' => $request->tecdoc_name
            ]);
            return back();
        }

        $suppliers = $this->tecdoc->getAllSuppliers();
        $alias = AliasBrand::paginate(40);
        return view('admin.no_brand.alias',compact('alias','suppliers'));
    }

    public function brandAliasDelete(Request $request){
        AliasBrand::destroy((int)$request->id);
        return back();
    }

    public function deleteNoBrandProduct(Request $request){
        $data = $request->except('_token');
        NoBrandProduct::where('brand','=',isset($data['brand_name'])?$data['brand_name']:'')
            ->delete();
        return redirect()->back()->with('status','Удаление прошло успешно');
    }
}
