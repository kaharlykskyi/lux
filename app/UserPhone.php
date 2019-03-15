<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    protected $table = 'user_phones';

    protected $fillable = [
        'user_id',
        'phone'
    ];

    public $timestamps = false;
}
