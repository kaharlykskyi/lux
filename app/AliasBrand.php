<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AliasBrand extends Model
{
    protected $table = 'alias_brands';

    protected $fillable = ['name','tecdoc_name'];

    public $timestamps = false;
}
