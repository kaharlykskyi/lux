<?php

namespace App\Http\Controllers\Admin;

use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::paginate(20);
        return view('admin.pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
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
            'title' => 'required|max:255',
            'footer_column' => 'required',
            'description' => 'string|nullable|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $data['user_id'] = Auth::id();

        $page = new Page();
        $page->fill($data);
        if ($page->save()){
            return redirect()->route('admin.page.index')->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        $pageObj = Page::where('id',$page->id)->first();
        return redirect()->route('page',$pageObj->alias);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit',compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'title' => 'required|max:255',
            'footer_column' => 'required',
            'description' => 'string|nullable|max:255',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $data['user_id'] = Auth::id();

        $page->update($data);
        if ($page->save()){
            return redirect()->back()->with('status','Данные сохранены');
        } else {
            return redirect()->back()->with('status','Данные не сохранены');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        try {
            $page->delete();
            return back()->with('status','Страница удалена');
        } catch (\Exception $e) {
            if (config('app.debug')){
                dump($e);
            }
        }
    }
}
