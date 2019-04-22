<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeCategoryGroup extends Model
{
    protected $table = 'home_category_groups';

    protected $fillable = [
        'name',
        'hurl',
        'key_words',
        'categories_id',
        'background',
        'img'
    ];

    public $timestamps = false;
}
