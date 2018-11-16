<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'alias',
        'short_description',
        'full_description',
        'price',
        'old_price',
        'stock',
        'count'
    ];
}
