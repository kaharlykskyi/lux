<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\TecDoc\ImportPriceList;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use \App\Services\Admin\Product as AdminProduct;

class ProductController extends Controller
{

    protected $tecdoc;

    protected $service;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->service = new AdminProduct();
    }

    public function setFilterAdminProduct(Request $request){
        if (isset($request->str_search) && isset($request->field)){
            if (session()->has("admin_filter.fields")){
                $buff = session("admin_filter.fields");
                array_push($buff,[
                    $request->field,
                    $request->str_search
                ]);
                session()->put("admin_filter.fields",$buff);
            }else{
                session()->put("admin_filter.fields",[
                    [
                        $request->field,
                        $request->str_search
                    ]
                ]);
            }
        }
        if (isset($request->clear_admin_filter)){
            session()->forget('admin_filter.fields');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session()->has("admin_filter.fields") && !empty(session("admin_filter.fields")))
        {
            $where = [];
            foreach (session("admin_filter.fields") as $filter){
                $where[] = [
                    $filter[0],'LIKE',"%{$filter[1]}%"
                ];
            }
            $products = Product::where($where)->paginate(80);
        }else{
            $products = Product::paginate(80);
        }
        return view('admin.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'articles' => 'required|max:255',
            'brand' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $data['price'] = floatval($data['price']);
        if (isset($data['old_price'])){
            $data['old_price'] = floatval($data['old_price']);
        }

        $product = new Product();

        $product->fill($data);
        if($product->save()){
            return redirect()->route('admin.product.index')->with('status','Товар добавлен');
        } else {
            return redirect()->back()->with('status','Ошибка, попробуйте снова');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->except(['_token','_method']);

        $validate = Validator::make($data,[
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'articles' => 'required|max:255',
            'brand' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $product->update($data);
        if ($product->save()){
            return back()->with('status','Данные Сохранены');
        }else{
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return back()->with('status','Товар удален');
        } catch (\Exception $e) {
            if (config('app.debug')){
                dump($e);
            } else {
                return back()->with('status','Товар не был удален');
            }
        }


    }

    public function startImport(){
        new ImportPriceList();

        return response()->json([
            'text' => 'Загрузка прошла успешно. Детальную информацию можно просмотреть в истории импортов'
        ]);
    }

    public function startExport(Request $request){

        $limit_export = isset($request->limit)?$request->limit:10000;

        $exportdata = $this->service->getExportData($limit_export);
        foreach ($exportdata as $k => $item){
            $exportdata[$k]->attribute = $this->service->getAttribute($item->articles,$item->SupplierId);
        }

        $xls_file = $this->service->createXlsFile($exportdata);

        return response()->download($xls_file);
    }
}
