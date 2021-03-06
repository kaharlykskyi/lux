<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use App\{Product,Provider,TecDoc\ImportPriceList,TecDoc\Tecdoc};
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \App\Services\Admin\Product as AdminProduct;

class ProductController extends Controller
{

    protected $tecdoc;

    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->service = new AdminProduct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $products = Product::where($this->filterProduct($request))
            ->paginate(80);
        $providers = Provider::all();
        $products->withPath($request->fullUrl());
        $suppliers = $this->tecdoc->getAllSuppliers();
        $manufacturers = $this->tecdoc->getBrands();
        return view('admin.product.index',compact('products','providers','suppliers','manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $providers = Provider::all();
        return view('admin.product.create',compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
            $file = $this->saveFile($request,$product);
            if ($file){
                $this->tecdoc->saveProductArt($product,$file,$request->file_description);
            }

            return redirect()->route('admin.product.index')->with('status','Товар добавлен');
        } else {
            return redirect()->back()->with('status','Ошибка, попробуйте снова');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        $providers = Provider::all();
        $files = $this->tecdoc->getArtFiles($product->articles,$product->brand);
        return view('admin.product.edit',compact('product','providers','files'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->except(['_token','_method']);

        $validate = Validator::make($data,[
            'name' => 'required|max:255',
            'articles' => 'required|max:255',
            'brand' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $file = $this->saveFile($request,$product);
        if ($file){
            $this->tecdoc->saveProductArt($product,$file,$request->file_description);
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
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return back()->with('status','Товар удален');
        } catch (Exception $e) {
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
        $exportdata = $this->service->getExportData($this->filterProduct($request));
        $xls_file = $this->service->createXlsFile($exportdata);
        return response()->download($xls_file);
    }

    public function startEaseImport(Request $request){

        if ($request->hasFile('price_list')){
            $file = $request->file('price_list');
            $file_name = time() . '_' . str_replace([' ','/','\\'],'',$file->getClientOriginalName());
            $file->move(storage_path('app') . '/import_ease/',$file_name);
        }

        if (isset($file_name) && isset($request->company)){
            $command = "php -f ".base_path()."/artisan easy-import:start {$request->company} {$file_name} >> /dev/null &";
            exec($command);
        }

        return back()->with('status','Импорт запущен');
    }

    public function incognitoFile(Request $request){
        if (isset($request->file)) {
            return response()->download(storage_path('app') . '/import_ease/' . $request->file);
        }else{
            return back();
        }
    }

    public function popularProduct(Request $request){

        $popular_products = DB::table('cart_products')
            ->join('products','products.id','=','cart_products.product_id')
            ->where($this->filterProduct($request))
            ->select('products.*',DB::raw('COUNT(cart_products.product_id) AS count_bay'))
            ->groupBy('cart_products.product_id')
            ->distinct()
            ->orderByDesc('count_bay')
            ->paginate(50);

        $providers = Provider::all();
        $suppliers = $this->tecdoc->getAllSuppliers();
        $manufacturers = $this->tecdoc->getBrands();

        return view('admin.product.popular',compact('popular_products','providers','suppliers','manufacturers'));
    }

    public function deleteFile(Request $request){
        if (File::exists(public_path('/product_imags/' . $request->brand . '/' . $request->name_file))){
            if ($this->tecdoc->deleteProductArt($request->brand,$request->article,$request->name_file)){
                File::delete(public_path('/product_imags/' . $request->brand . '/' . $request->name_file));
            }
        }

        return back();
    }

    protected function saveFile(Request $request, Product $product)
    {
        if($request->hasfile('product_file'))
        {
            $file = $request->file('product_file');
            $extension = $file->getClientOriginalExtension();
            $filename = $product->brand . '_' . time().'.'.$extension;
            return $file->move('product_imags/' . $product->brand . '/', $filename);
        }

        return false;
    }

    protected function filterProduct($request)
    {
        $filter = [];

        if (isset($request->prov_min_price) && !empty($request->prov_min_price)) $filter[] = ['provider_price','>=',(int)$request->prov_min_price];
        if (isset($request->prov_max_price) && !empty($request->prov_max_price)) $filter[] = ['provider_price','<=',(int)$request->prov_max_price];
        if (isset($request->min_price) && !empty($request->min_price)) $filter[] = ['price','>=',(int)$request->min_price];
        if (isset($request->max_price) && !empty($request->max_price)) $filter[] = ['price','<=',(int)$request->max_price];
        if (isset($request->provider) && !empty($request->provider)) $filter[] = ['provider_id','=',(int)$request->provider];
        if (isset($request->name) && !empty($request->name)) $filter[] = ['name','LIKE',"%{$request->name}%"];
        if (isset($request->article) && !empty($request->article)) $filter[] = ['articles','LIKE',"%{$request->article}%"];
        if (isset($request->supplier) && !empty($request->supplier)) $filter[] = ['brand','LIKE',"%{$request->supplier}%"];
        if (isset($request->count) && !empty($request->count)) $filter[] = ['count','>=',(int)$request->count];

        return $filter;
    }
}
