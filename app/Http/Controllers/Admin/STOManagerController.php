<?php

namespace App\Http\Controllers\Admin;

use App\STOClients;
use App\STOWork;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class STOManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = STOClients::paginate(80);
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
            'vin' => 'required|max:255',
            'sum' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $data['sum'] = (float)$data['sum'];

        $stoClient = new STOClients();
        $stoClient->fill($data);

        if ($stoClient->save()){
            foreach ($data['product_article'] as $k => $item){
                if (!empty($item) && !empty($data['product_name'][$k])){
                    $stoWork = new STOWork();
                    $stoWork->fill([
                        'sto_clint_id' => $stoClient->id,
                        'article_operation' => $item,
                        'name' => $data['product_name'][$k],
                        'count' => $data['product_col'][$k],
                        'price' => (float)$data['product_price'][$k],
                        'price_discount' => (float)$data['product_price_discount'][$k]
                    ]);
                    $stoWork->save();
                }
            }
        }

        return redirect()->route('admin.sto_manager.index')->with('status','Запись добавлена');
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
        $sto_client = STOClients::with('work')->where('id',(int)$request->sto_manager)->first();
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
            'vin' => 'required|max:255',
            'sum' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $delete = explode(',',$data['delete_work']);

        STOClients::where('id',(int)$request->sto_manager)->update([
            'fio' => $data['fio'],
            'num_auto' => $data['num_auto'],
            'brand' => $data['brand'],
            'vin' => $data['vin'],
            'data' => $data['data'],
            'sum' => $data['sum']
        ]);

        foreach ($data['id'] as $k => $item){
            if ($item !== 'new'){
                if (in_array($item,$delete)){
                    STOWork::where('id',(int)$item)->delete();
                }else{
                    if (!empty($item) && !empty($data['product_name'][$k])){
                        STOWork::where('id',(int)$item)->update([
                            'article_operation' => $data['product_article'][$k],
                            'name' => $data['product_name'][$k],
                            'count' => $data['product_col'][$k],
                            'price' => (float)$data['product_price'][$k],
                            'price_discount' => (float)$data['product_price_discount'][$k]
                        ]);
                    }
                }
            }else{
                $stoWork = new STOWork();
                $stoWork->fill([
                    'sto_clint_id' => (int)$request->sto_manager,
                    'article_operation' => $data['product_article'][$k],
                    'name' => $data['product_name'][$k],
                    'count' => $data['product_col'][$k],
                    'price' => (float)$data['product_price'][$k],
                    'price_discount' => (float)$data['product_price_discount'][$k]
                ]);
                $stoWork->save();
            }
        }

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
        return redirect()->back()->with('status','Запись удалена');
    }

}
