<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FastBuy extends Model
{
    protected $table = 'fast_buy';

    protected $fillable = ['phone','product_id'];
}
