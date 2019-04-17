<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected $table = 'cart_products';

    protected $fillable = ['cart_id','product_id','count'];

    public function cart(){
        return $this->belongsTo(Cart::class,'cart_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
