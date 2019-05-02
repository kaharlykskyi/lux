<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Catalog
{
    public function getMinMaxPrice($level,$param){
        switch ($level){
            case 'search_str':
                $price = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.articles'),'LIKE',"%{$param['str']}%")
                    ->orWhere(DB::raw('p.name'),'LIKE',"%{$param['str']}%")
                    ->select(DB::raw(' MIN(p.price) AS min, MAX(p.price) AS max'))
                    ->get();
                return $price[0];
                break;
            case 'category':
                $price = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.linkageid'),(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('MIN(p.price) AS min, MAX(p.price) AS max'))
                    ->get();
                return $price[0];
                break;
            case 'pcode':
                $price = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_cross AS ac'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('ac.SupplierId'),DB::raw('sp.id'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'))
                    ->where(DB::raw('ac.OENbr'),$param['OENbr'])
                    ->where(DB::raw('ac.manufacturerId'),(int)$param['manufacturer'])
                    ->select(DB::raw('MIN(p.price) AS min, MAX(p.price) AS max'))
                    ->get();
                return $price[0];
                break;
        }
    }

    public function getBrands($level,$param){
        switch ($level){
            case 'search_str':
                return DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->orWhere([
                        [DB::raw('p.articles'),'LIKE',"%{$param['str']}%",'OR'],
                        [DB::raw('p.name'),'LIKE',"%{$param['str']}%",'OR']
                    ])
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('sp.matchcode'),DB::raw('p.brand'))
                    ->select(DB::raw('sp.id AS supplierId, sp.description'))
                    ->distinct()
                    ->get();
                break;
            case 'category':
                return DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.linkageid'),(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('s.id AS supplierId, s.description'))
                    ->distinct()
                    ->get();
                break;
            case 'pcode':
                return DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_cross AS ac'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('ac.SupplierId'),DB::raw('sp.id'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'))
                    ->where(DB::raw('ac.OENbr'),$param['OENbr'])
                    ->where(DB::raw('ac.manufacturerId'),(int)$param['manufacturer'])
                    ->select(DB::raw('sp.id AS supplierId, sp.description'))
                    ->distinct()
                    ->get();
                break;
            default:
                return [];
        }
    }

    public function getAttributes($level,$param,$filters){

        foreach ($filters as $filter){
            $attr_ids[] = $filter->filter_id;
        }

        $filters_data = [];
        if (isset($attr_ids)){
            switch ($level){
                case 'search_str':
                    $filters_data = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('attr.DataSupplierArticleNumber'),'=',DB::raw('p.articles'))
                        ->where([
                            [DB::raw('p.articles'),'LIKE',"%{$param['str']}%",'OR'],
                            [DB::raw('p.name'),'LIKE',"%{$param['str']}%",'OR']
                        ])
                        ->whereIn('attr.id',$attr_ids)
                        ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                        ->distinct()
                        ->get();
                    break;
            }
        }

        $parse_filter_data = [];
        foreach ($filters as $filter){
            $parse_filter_data[$filter->filter_id]['description'] = $filter->description;
            $parse_filter_data[$filter->filter_id]['hurl'] = $filter->hurl . '_' . $filter->filter_id;
            foreach ($filters_data as $item){
                if ((int)$filter->filter_id === (int)$item->id){
                    $parse_filter_data[$filter->filter_id]['filter_item'][] = $item;
                }
            }
        }

        return $parse_filter_data;
    }
}
