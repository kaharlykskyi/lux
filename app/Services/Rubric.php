<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Rubric
{
    public function getSubCategory($categories_id){
        return DB::table('all_category_trees')
            ->whereIn('id',$categories_id)
            ->get();
    }
}
