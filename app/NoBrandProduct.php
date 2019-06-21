<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoBrandProduct extends Model
{
    protected $table = 'no_brand_products';

    protected $fillable = [
        'name',
        'articles',
        'short_description',
        'full_description',
        'price',
        'old_price',
        'provider_id',
        'brand',
        'count',
        'delivery_time',
        'provider_price',
        'provider_currency',
        'stocks'
    ];
}
