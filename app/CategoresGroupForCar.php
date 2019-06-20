<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoresGroupForCar extends Model
{
    protected  $table = 'categores_group_for_cars';

    protected $fillable = ['title','logo','categories'];

    public $timestamps = false;
}
