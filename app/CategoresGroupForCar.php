<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoresGroupForCar extends Model
{
    protected  $table = 'categores_group_for_cars';

    protected $fillable = ['title','logo','categories','parent_id'];

    public $timestamps = false;

    public function childCategories(){
        return $this->hasMany(CategoresGroupForCar::class,'parent_id');
    }
}
