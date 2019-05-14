<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STOСheck extends Model
{
    protected $table = 's_t_o_сhecks';

    protected $fillable = [
        'sum',
        'sto_clint_id',
        'info_for_user',
        'price_abc',
        'acceptor',
        'application_date',
        'date_compilation',
        'place'
    ];

    public function work(){
        return $this->hasMany(STOWork::class,'sto_check_id')->orderByDesc('created_at');
    }

    public function client(){
        return $this->belongsTo(STOClients::class,'sto_clint_id');
    }
}
