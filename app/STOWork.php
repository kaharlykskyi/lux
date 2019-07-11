<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STOWork extends Model
{
    protected $table = 's_t_o_works';

    protected $fillable = [
        'sto_check_id',
        'article_operation',
        'name',
        'count',
        'price',
        'type'
    ];
}
