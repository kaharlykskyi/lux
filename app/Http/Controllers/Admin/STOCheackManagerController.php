<?php

namespace App\Http\Controllers\Admin;

use App\{STOClients, StoreSettings, STOWork, STOСheck, Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class STOCheackManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $client = STOClients::findOrFail((int)$request->client);
        $checks = STOClients::find((int)$request->client)->check()->paginate(50);
        return view('admin.sto_checks.index',compact('client','checks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sto_checks.create');
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

        $data['sum'] = (float)$data['sum'];

        $stoCkeck = new STOСheck();
        $stoCkeck->fill($data);

        if ($stoCkeck->save()){
            foreach ($data['product_article'] as $k => $item){
                if (!empty($item) && !empty($data['product_name'][$k])){
                    $stoWork = new STOWork();
                    $stoWork->fill([
                        'sto_check_id' => $stoCkeck->id,
                        'article_operation' => $item,
                        'name' => $data['product_name'][$k],
                        'count' => $data['product_col'][$k],
                        'price' => (float)$data['product_price'][$k],
                        'price_discount' => (float)$data['product_price_discount'][$k],
                        'type' => $data['type'][$k]
                    ]);
                    $stoWork->save();
                }
            }
        }

        return redirect()->route('admin.sto_check_manager.index',['client' => $data['sto_clint_id']])
            ->with('status','Запись добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $check = STOСheck::with('work')->findOrFail($id);
        $settings = StoreSettings::where('type','company')->first();
        $decode_company_data = json_decode($settings->settings);
        return view('admin.sto_checks.show',compact('check','decode_company_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $check = STOСheck::with('work')->findOrFail($id);
        return view('admin.sto_checks.edit',compact('check'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_token','_method']);
        $delete = explode(',',$data['delete_work']);

        STOСheck::where('id',$id)->update([
            'sum' => $data['sum'],
            'info_for_user' => $data['info_for_user'],
            'price_abc' => $data['price_abc'],
            'acceptor' => $data['acceptor'],
            'application_date' => $data['application_date'],
            'date_compilation' => $data['date_compilation'],
            'place' => $data['place']
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
                            'price_discount' => (float)$data['product_price_discount'][$k],
                            'type' => $data['type'][$k]
                        ]);
                    }
                }
            }else{
                $stoWork = new STOWork();
                $stoWork->fill([
                    'sto_check_id' => $id,
                    'article_operation' => $data['product_article'][$k],
                    'name' => $data['product_name'][$k],
                    'count' => $data['product_col'][$k],
                    'price' => (float)$data['product_price'][$k],
                    'price_discount' => (float)$data['product_price_discount'][$k],
                    'type' => $data['type'][$k]
                ]);
                $stoWork->save();
            }
        }

        return back()->with('status','Данные обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        STOСheck::where('id',$id)->delete();
        return redirect()->back()->with('status','Чек удалён');
    }


    public function pdfGenerator(Request $request){
        $check = STOСheck::with(['work','client'])->where('id',(int)$request->check)->first();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->makeSTOCheckTemplate($check));
        return $pdf->stream();
    }

    private function makeSTOCheckTemplate($check){
        $company_data = StoreSettings::where('type','company')->first();
        $decode_company_data = json_decode($company_data->settings);
        return view('admin.pdf_template.sto_check',compact('check','decode_company_data'));
    }
}
