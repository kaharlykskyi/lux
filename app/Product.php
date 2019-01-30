<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'articles',
        'short_description',
        'full_description',
        'price',
        'old_price',
        'company',
        'brand'
    ];
}
