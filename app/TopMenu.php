<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopMenu extends Model
{
    protected $table = 'top_menu';

    protected $fillable = [
        'tecdoc_id',
        'tecdoc_title',
        'show_menu',
        'title'
    ];

    protected $attributes = [
        'show_menu' => 0,
        'tecdoc_id' => 0
    ];

    public $timestamps = false;
}
