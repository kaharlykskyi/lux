<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\TecDoc\ImportPriceList;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Psy\debug;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }
}
