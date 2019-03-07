<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 26.02.2019
 * Time: 17:07
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Rubric
{
    public function getSubCategory($parent,$level = 1){
        switch ($level){
            case 1:
                return cache()->remember('sub_' . $parent, 60*24, function () use ($parent) {
                    return $this->getSubCategoryQuery($parent);
                });
                break;
            case 2:
                return cache()->remember('sub_' . $parent, 60*24, function () use ($parent) {
                    return $this->getSubCategoryQuery($parent);
                });
                break;
        }
    }

    private function getSubCategoryQuery($parent){
        return DB::connection('mysql_tecdoc')
            ->table('passanger_car_trees')
            ->where('parentid',(int)$parent)
            ->select('id','description')
            ->distinct()
            ->get();
    }
}