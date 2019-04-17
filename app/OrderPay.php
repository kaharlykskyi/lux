<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPay extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'cart_id',
        'user_id',
        'success_pay',
        'price_pay',
        'seen'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
