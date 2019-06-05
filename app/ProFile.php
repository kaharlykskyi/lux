<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProFile extends Model
{
    protected $table = 'pro_files';

    protected $fillable = [
        'name',
        'provider_id',
        'col_provider',
        'data_row',
        'articles',
        'product_name',
        'brand',
        'price',
        'currency',
        'delivery_time',
        'stocks',
        'static_name',
        'static_email1',
        'static_email2',
        'active_sheet'
    ];

    public function provider(){
        return $this->belongsTo('App\Provider','provider_id');
    }
}
