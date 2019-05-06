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
        'sum'
    ];

    public function work(){
        return $this->hasMany(STOWork::class,'sto_clint_id');
    }
}
