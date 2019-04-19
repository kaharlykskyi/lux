<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCar extends Model
{
    protected $table = 'user_cars';

    protected $fillable = [
        'user_id',
        'vin_code',
        'type_auto',
        'year_auto',
        'brand_auto',
        'model_auto',
        'modification_auto',
        'body_auto'
    ];
}
