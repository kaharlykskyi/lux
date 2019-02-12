<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'tecdoc_id',
        'name',
        'type',
        'logo'
    ];

    public $timestamps = false;
}
