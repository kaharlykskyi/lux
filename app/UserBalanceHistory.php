<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBalanceHistory extends Model
{
    protected $table = 'user_balance_history';

    protected $fillable = ['user_id','balance_refill'];
}
