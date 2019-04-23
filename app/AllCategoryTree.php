<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllCategoryTree extends Model
{
    protected $table = 'all_category_trees';

    protected $fillable = [
        'parent_category',
        'hurl',
        'tecdoc_id',
        'name',
        'image',
        'show',
        'tecdoc_name',
        'level'
    ];

    public $timestamps = false;

    public function subCategory(){
        return $this->hasMany(AllCategoryTree::class,'parent_category');
    }
}
