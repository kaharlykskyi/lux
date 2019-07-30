<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoresGroupForCar extends Model
{
    protected  $table = 'categores_group_for_cars';

    protected $fillable = ['title','logo','categories','parent_id','range'];

    public $timestamps = false;

    public function childCategories(){
        return $this->hasMany(CategoresGroupForCar::class,'parent_id')->orderByDesc(DB::raw('-`range`'));
    }
}
