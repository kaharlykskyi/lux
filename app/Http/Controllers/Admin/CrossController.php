<?php

namespace App\Http\Controllers\Admin;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CrossController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){

        $cross = [];
        $brands = $this->tecdoc->getBrands();
        if (isset($request->brand) || isset($request->OENbr)){
            $cross = $this->tecdoc->getCross(
                isset($request->brand)?(int)$request->brand:null,
                isset($request->OENbr)?$request->OENbr:null,
                isset($request->page)?$request->page:null
            );
        }else{
            $cross = $this->arrayPaginator($cross,$request,30);
        }

        $cross->withPath($request->fullUrl());

        return view('admin.cross.index',compact('cross','brands'));
    }

    public function create(Request $request){
        if ($request->isMethod('post')){
            $data = $request->except('_token');

            $res = DB::connection('mysql_tecdoc')
                ->table('article_cross')
                ->updateOrInsert([
                    "manufacturerId" => (int)$data['manufacturerId'],
                    "PartsDataSupplierArticleNumber" => "'{$data['PartsDataSupplierArticleNumber']}'",
                    "SupplierId" => (int)$data['SupplierId'],
                    "OENbr" => "'{$data['OENbr']}'",
                ]);

            if ($res){
                return redirect()->route('admin.cross.edit',['manufacturerId' => $data['manufacturerId'],'OENbr' => $data['OENbr'],'PartsDataSupplierArticleNumber' => $data['PartsDataSupplierArticleNumber'],'SupplierId' => $data['SupplierId']])->with('status','Кросс номер добавлен');
            } else {
                return back()->with('status','Данные не сохранены')->withInput();
            }
        }

        $brands = $this->tecdoc->getBrands();
        $providers = $this->tecdoc->getAllSuppliers();

        return view('admin.cross.create',compact('brands','providers'));
    }

    public function edit(Request $request){
        if ($request->isMethod('post')){
            $data = $request->except('_token');
            $res = DB::connection('mysql_tecdoc')
                ->table('article_cross')
                ->updateOrInsert([
                    "manufacturerId" => (int)$data['manufacturerId'],
                    "PartsDataSupplierArticleNumber" => "'{$data['PartsDataSupplierArticleNumber']}'",
                    "SupplierId" => (int)$data['SupplierId'],
                    "OENbr" => "'{$data['OENbr']}'",
                ]);

            if ($res){
                return back()->with('status','Кросс номер обновлён');
            } else {
                return back()->with('status','Данные не обновлены');
            }
        }

        $brands = $this->tecdoc->getBrands();
        $providers = $this->tecdoc->getAllSuppliers();
        return view('admin.cross.edit',compact('brands','providers'));
    }

    public function delete(Request $request){
        if (isset($request->manufacturerId) && isset($request->OENbr) && isset($request->PartsDataSupplierArticleNumber) && isset($request->SupplierId)) {
            DB::connection('mysql_tecdoc')
                ->table('article_cross')
                ->where('manufacturerId',(int)$request->manufacturerId)
                ->where('OENbr',$request->OENbr)
                ->where('PartsDataSupplierArticleNumber',$request->PartsDataSupplierArticleNumber)
                ->where('SupplierId',(int)$request->SupplierId)
                ->delete();
        }

        return back()->with('status','Кросс удалён');
    }
}
