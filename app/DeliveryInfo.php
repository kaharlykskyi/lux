<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryInfo extends Model
{
    protected $table = 'delivery_info';

    protected $fillable = [
        'user_id',
        'delivery_country',
        'delivery_city',
        'street',
        'house',
        'phone',
        'delivery_service',
        'delivery_department'
    ];
}
