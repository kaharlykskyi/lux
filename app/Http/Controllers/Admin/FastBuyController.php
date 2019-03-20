<?php

namespace App\Http\Controllers\Admin;

use App\FastBuy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FastBuyController extends Controller
{
    protected $fast_buy = null;

    protected $prePage = 30;

    public function index(Request $request){
        if(isset($request->fust_buy) && isset($request->data)){
            FastBuy::where('id',$request->fust_buy)->update([
                'status' => $request->data === 'on' ? 1:0
            ]);

            return response()->json([
                'response' => 'Статус обновлён'
            ]);
        }

        if ($request->status === 'new'){
            $this->fast_buy = FastBuy::with('product')->where('status',0)->paginate($this->prePage);
        } else {
            $this->fast_buy = FastBuy::with('product')->where('status',1)->paginate($this->prePage);
        }

        return view('admin.fast_buy.index')->with([
            'fast_buy' => $this->fast_buy
        ]);
    }
}
