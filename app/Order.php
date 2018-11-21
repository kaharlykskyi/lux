<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'payments';

    protected $fillable = ['cart_id','user_id','success_pay','price_pay'];
}