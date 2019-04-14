<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';

    protected $fillable = [
        'name',
        'currency',
        'email',
        'phone',
        'desc'
    ];

    public $timestamps = false;
}
