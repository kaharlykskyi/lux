<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallOrder extends Model
{
    protected  $table = 'call_orders';

    protected $fillable = [
        'name',
        'phone',
        'status'
    ];
}
