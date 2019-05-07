<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreSettings extends Model
{
    protected $table = 'store_settings';

    protected $fillable = ['type','settings'];

    public $timestamps = false;
}
