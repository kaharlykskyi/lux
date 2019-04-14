<?php

namespace App\Http\Controllers\Admin;

use App\ProFile;
use App\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_files = ProFile::paginate(20);
        return view('admin.pro_file.index',compact('pro_files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::get();
        return view('admin.pro_file.create',compact('providers'));
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
            'data_row' => 'required',
            'articles' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'stocks' => 'required',
            'brand' => 'required',
            'static_name' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $profile = new ProFile();
        $profile->fill($data);
        if ($profile->save()){
            return redirect()->route('admin.pro_file.index')->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProFile  $proFile
     * @return \Illuminate\Http\Response
     */
    public function show(ProFile $proFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProFile  $proFile
     * @return \Illuminate\Http\Response
     */
    public function edit(ProFile $proFile)
    {
        $providers = Provider::get();
        return view('admin.pro_file.edit',compact('proFile','providers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProFile  $proFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProFile $proFile)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'name' => 'required|max:255',
            'data_row' => 'required',
            'articles' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'stocks' => 'required',
            'brand' => 'required',
            'static_name' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        if ($proFile->update($data)){
            return redirect()->back()->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProFile  $proFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProFile $proFile)
    {
        try {
            $proFile->delete();
            return back()->with('status','Профайл удалён');
        } catch (\Exception $e) {
            if (config('app.debug')){
                dump($e);
            }
        }
    }
}
