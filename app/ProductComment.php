<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $table = 'product_comments';

    protected $fillable = [
        'product_id',
        'user_id',
        'text',
        'rating'
    ];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
}
