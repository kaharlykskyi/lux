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

    public function cart(){
        return $this->belongsToMany(Cart::class,'cart_products');
    }

    public function stock(){
        return $this->belongsToMany(Stock::class,'stock_products')->withPivot('count');
    }
}
