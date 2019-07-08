<?php


namespace App\Services;


use App\Http\Controllers\LiqPayController;
use App\UserBalanceHistory;

final class CheckPayStatus
{
    /**
     * Singleton obj
     * @var $instance
     */
    private static $instance;

    private function __construct(){
        self::index();
    }

    /**
     * gets the instance via lazy initialization (created on first usage or return false when obj is created)
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
            return static::$instance;
        }

        return false;
    }

    private static function index(){
        $pay = UserBalanceHistory::where('status','=',0)
            ->whereIn('liqpay_status',[
                'wait_accept',
                'p24_verify',
                '3ds_verify',
                'captcha_verify',
                'cvv_verify',
                'ivr_verify',
                'otp_verify',
                'password_verify',
                'phone_verify',
                'pin_verify',
                'receiver_verify',
                'sender_verify',
                'senderapp_verify',
                'wait_qr',
                'wait_sender',
                'mp_verify',
                'cash_wait',
                'hold_wait',
                'invoice_wait',
                'prepared',
                'processing',
                'wait_card',
                'wait_compensation',
                'wait_lc',
                'wait_reserve',
                'wait_secure',
                'try_again'
            ])->get();

        foreach ($pay as $item){
            try{
                LiqPayController::changeStatusPay(json_decode(json_encode($item), true));
            }catch (\Exception $exception){
                report($exception);
            }
        }
    }
}