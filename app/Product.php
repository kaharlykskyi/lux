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
        'provider_id',
        'brand',
        'count',
        'delivery_time',
        'provider_price',
        'provider_currency',
        'stocks',
        'original'
    ];

    public function cart(){
        return $this->belongsToMany(Cart::class,'cart_products');
    }

    public function comment(){
        return $this->hasMany(ProductComment::class)->orderByDesc('created_at');
    }

    public function provider(){
        return $this->belongsTo(Provider::class,'provider_id');
    }
}
