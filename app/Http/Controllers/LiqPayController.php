<?php

namespace App\Http\Controllers;

use App\UserBalance;
use App\UserBalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LiqPay;

class LiqPayController extends Controller
{
    protected $public_key;

    protected $private_key;

    public function __construct(Request $request)
    {
        parent::__construct($request);
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
            'server_url'     => route('liqpay.response'),
            'result_url'     => route('liqpay.result_pay')
        ));

        return view('liqpay.pay_redirect')->with([
            'form' => $html
        ]);
    }

    public function getLiqPayResponse(Request $request){
        $signature = $request->post('signature');
        $data = $request->post('data');

        if (isset($signature) && isset($data)){
            $liqpay = new LiqPay($this->public_key, $this->private_key);

            $signature_decode = $liqpay->str_to_sign($this->private_key . $data . $this->private_key);
            if ($signature === $signature_decode){
                $params = $liqpay->decode_params($data);

                if ($params['status'] === 'success'){
                    $amount = $params['amount'] - $params['sender_commission'] - $params['receiver_commission'] - $params['commission_credit'] - $params['commission_debit'];
                    UserBalanceHistory::where('id',$params['order_id'])->update([
                        'balance_refill' => $amount,
                        'status' => true
                    ]);

                    $balanseHistiry =  UserBalanceHistory::where('id',$params['order_id'])->first();
                    if (DB::table('user_balance')->where('user_id',$balanseHistiry->user_id)->exists()){
                        $oldBalance = UserBalance::where('user_id',$balanseHistiry->user_id)->first();
                        UserBalance::where('user_id',$balanseHistiry->user_id)->update([
                            'balance' => (float)$oldBalance->balance + $amount
                        ]);
                    } else {
                        $userBalance = new UserBalance();
                        $userBalance->fill([
                            'user_id' => $balanseHistiry->user_id,
                            'balance' => $amount
                        ]);
                        $userBalance->save();
                    }
                }
            }
        }
    }

    public function resultPay(){
        $lats_pay_user = UserBalanceHistory::where('user_id',Auth::id())->latest()->first();
        $liqpay = new LiqPay($this->public_key, $this->private_key);
        $status_pay = $liqpay->api("request", array(
            'action'        => 'status',
            'version'       => '3',
            'order_id'      => $lats_pay_user->id
        ));
        return view('liqpay.success_pay',compact('status_pay'));
    }

    public function checkPayStatus(Request $request){
        $pay_user = UserBalanceHistory::where([
            ['user_id',Auth::id()],
            ['id',(int)$request->id]
        ])->first();

        $liqpay = new LiqPay($this->public_key, $this->private_key);
        $status_pay = $liqpay->api("request", array(
            'action'        => 'status',
            'version'       => '3',
            'order_id'      => $pay_user->id
        ));

        return response()->json([
            'pay' => $pay_user,
            'liqpay_data' => $status_pay
        ]);
    }
}
