<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OderStatus extends Model
{
    protected $table = 'oder_status_codes';

    protected $fillable = ['name'];

    public $timestamps = false;
}
