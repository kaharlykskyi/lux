<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopMenu extends Model
{
    protected $table = 'top_menu';

    protected $fillable = [
        'tecdoc_category',
        'show_menu',
        'title'
    ];

    protected $attributes = [
        'show_menu' => 0
    ];



    public $timestamps = false;
}
