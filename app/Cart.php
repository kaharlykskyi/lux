<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['user_id','oder_status','session_id','invoice_np','manager_id','oder_dt','seen'];

    public function cartProduct(){
        return $this->belongsToMany(Product::class,'cart_products')->withPivot(['count','id']);
    }

    public function status(){
        return $this->belongsTo(OderStatus::class,'oder_status');
    }

    public function manager(){
        return$this->belongsTo(User::class,'manager_id');
    }

    public function client(){
        return$this->belongsTo(User::class,'user_id');
    }

    public function payOder(){
        return $this->hasOne(OrderPay::class,'cart_id');
    }
}
