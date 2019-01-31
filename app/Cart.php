<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['user_id','oder_status','session_id','invoice_np'];

    public function cartProduct(){
        return $this->belongsToMany(Product::class,'cart_products')->withPivot('count');
    }
}
