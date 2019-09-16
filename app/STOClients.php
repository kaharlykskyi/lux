<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STOClients extends Model
{
    protected $table = 's_t_o_clients';

    protected $fillable = [
        'fio',
        'num_auto',
        'brand',
        'vin',
        'data',
        'car_name',
        'phone',
    ];

    public function check(){
        return $this->hasMany(STOСheck::class,'sto_clint_id');
    }
}
