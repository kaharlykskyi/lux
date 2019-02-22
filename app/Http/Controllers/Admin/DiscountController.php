<?php

namespace App\Http\Controllers\Admin;

use App\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discount = Discount::paginate(20);
        return view('admin.discount.index',compact('discount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.discount.create');
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
            'percent' => 'required|numeric',
            'description' => 'string|nullable|max:255',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $discount = new Discount();
        $discount->fill($data);
        if ($discount->save()){
            return redirect()->route('admin.discount.index')->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        return view('admin.discount.edit',compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'percent' => 'required|numeric',
            'description' => 'string|nullable|max:255',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $discount->update($data);
        if ($discount->save()){
            return redirect()->back()->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();
            return back()->with('status','Скидка удалена');
        } catch (\Exception $e) {
            if (config('app.debug')){
                dump($e);
            }
        }
    }
}
