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
        'mileage',
        'vin',
        'data',
        'sum',
        'info_for_user',
        'price_abc',
        'acceptor',
        'application_date',
        'date_compilation',
        'car_name',
        'phone',
        'place'
    ];

    public function work(){
        return $this->hasMany(STOWork::class,'sto_clint_id');
    }
}
