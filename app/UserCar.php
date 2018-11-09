<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCar extends Model
{
    protected $table = 'user_cars';

    protected $fillable = [
      'user_id',
      'vin_code',
      'mark',
      'year',
      'model',
      'v_motor',
      'type_motor'
    ];
}
