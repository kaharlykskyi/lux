<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['img','link','str_link','text'];

    public $timestamps = false;
}
