<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutualSettlement extends Model
{
    protected $table = 'mutual_settlements';

    protected $fillable = [
        'description',
        'type_operation',
        'user_id',
        'currency',
        'change',
        'balance'
    ];
}
