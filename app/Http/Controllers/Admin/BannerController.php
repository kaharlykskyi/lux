<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Banner::paginate(20);
        return view('admin.banner.index',compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner.create');
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
            'img' => 'required|file',
            'link' => 'required|max:255|url',
            'str_link' => 'string|nullable|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        if ($request->hasFile('img')){

            $file = $request->file('img');
            $file_name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/images/banner_img/',$file_name);
            $data['img'] = $file_name;
        }

        $slide = new Banner();
        $slide->fill($data);
        if ($slide->save()){
            return redirect()->route('admin.banner.index')->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit',compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'img' => 'nullable|file',
            'link' => 'required|max:255|url',
            'str_link' => 'string|nullable|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        if ($request->hasFile('img')){

            if (isset($banner) && file_exists(public_path() . '/images/banner_img/' . $banner->img)){
                unlink(public_path() . '/images/banner_img/' . $banner->img);
            }

            $file = $request->file('img');
            $file_name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/images/banner_img/',$file_name);
        }

        Banner::where('id',$banner->id)->update([
            'img' => isset($file_name)?$file_name:$banner->img,
            'link' => $data['link'],
            'str_link' => $data['str_link'],
            'text' => $data['text'],
        ]);

        return redirect()->back()->with('status','Данные сохранены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner $banner
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        if (isset($banner) && file_exists(public_path() . '/images/banner_img/' . $banner->img)){
            unlink(public_path() . '/images/banner_img/' . $banner->img);
        }

        $banner->delete();
        return back()->with('status','Слайдер удалён');
    }
}
