<?php

namespace App\Http\Controllers\Admin;

use App\{STOClients, Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class STOManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [];
        if (isset($request->fio_user) && !empty($request->fio_user)) $filter[] = ['fio','LIKE',"%{$request->fio_user}%"];
        if (isset($request->date_crate) && !empty($request->date_crate)) $filter[] = ['data',$request->date_crate];
        if (isset($request->phone_user) && !empty($request->phone_user)) $filter[] = ['phone','LIKE',"%{$request->phone_user}%"];

        $clients = STOClients::where($filter)->orderBy('created_at', 'desc')->paginate(80);
        return view('admin.sto_clients.index',compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sto_clients.create');
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
            'fio' => 'required|max:255',
            'num_auto' => 'required|max:255',
            'brand' => 'required|max:255',
            'vin' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }


        $stoClient = new STOClients();
        $stoClient->fill($data);
        if ($stoClient->save()){
            return redirect()->route('admin.sto_manager.index')->with('status','Запись добавлена');
        }else{
            return back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\STOClients  $sTOClients
     * @return \Illuminate\Http\Response
     */
    public function show(STOClients $sTOClients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $sto_client = STOClients::findOrFail((int)$request->sto_manager);
        return view('admin.sto_clients.edit',compact('sto_client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token','_method']);

        $validate = Validator::make($data,[
            'fio' => 'required|max:255',
            'num_auto' => 'required|max:255',
            'brand' => 'required|max:255',
            'vin' => 'required|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        STOClients::where('id',(int)$request->sto_manager)->update($data);

        return redirect()->back()->with('status','Данные обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        STOClients::where('id',(int)$request->sto_manager)->delete();
        return redirect()->back()->with('status','Клиент удалён');
    }

}
