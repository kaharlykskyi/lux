<?php

namespace App\Http\Controllers;

use App\UserBalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use LiqPay;

class LiqPayController extends Controller
{
    protected $public_key;

    protected $private_key;

    public function __construct()
    {
        $this->private_key = config('liqpay.private_key');
        $this->public_key = config('liqpay.public_key');
    }

    public function index(){
        return view('liqpay.index');
    }

    public function sendPayRequest(Request $request){
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'amount' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $balanceHistory = new UserBalanceHistory();
        $balanceHistory->fill([
            'user_id' => Auth::id(),
            'balance_refill' => $data['amount'],
            'status' => false
        ]);
        $balanceHistory->save();

        $liqpay = new LiqPay($this->public_key, $this->private_key);

        $html = $liqpay->cnb_form(array(
            'action'         => 'pay',
            'amount'         => "{$data['amount']}",
            'currency'       => 'UAH',
            'description'    => 'Пополнение баланса на LuxAuto',
            'order_id'       => "{$balanceHistory->id}",
            'version'        => '3',
            'sandbox'        => '1', //TODO:delete on production
            'server_url'     => route('liqpay.response'),
            'result_url'     => route('liqpay.result_pay')
        ));

        return view('liqpay.pay_redirect')->with([
            'form' => $html
        ]);
    }

    public function getLiqPayResponse(Request $request){
        Storage::disk('local')->put('data.txt', $request->post('data'));
        Storage::disk('local')->put('signature.txt', $request->post('signature'));
    }

    public function resultPay(Request $request){

    }
}
