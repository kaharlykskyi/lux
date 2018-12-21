<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\TecDoc\ImportPriceList;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(80);
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

        $data['alias'] = str_replace(' ','_',$this->transliterateRU($data['name'] .'_'. $data['articles'] .'_'. $data['company']));
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
        //
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
        //
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
}
