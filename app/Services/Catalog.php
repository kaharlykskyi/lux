<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 07.03.2019
 * Time: 18:25
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Catalog
{
    public function getMinPrice($level,$param){
        switch ($level){
            case 'search_str':
                $min = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.articles'),'LIKE',"%{$param['str']}%")
                    ->orWhere(DB::raw('p.name'),'LIKE',"%{$param['str']}%")
                    ->select(DB::raw(' MIN(p.price) AS min'))
                    ->get();
                return round($min[0]->min,2);
                break;
            case 'category':
                $min = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.linkageid'),(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('MIN(p.price) AS min'))
                    ->get();
                return round($min[0]->min,2);
                break;
            case 'pcode':
                $min = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_cross AS ac'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('ac.SupplierId'),DB::raw('sp.id'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'))
                    ->where(DB::raw('ac.OENbr'),$param['OENbr'])
                    ->where(DB::raw('ac.manufacturerId'),(int)$param['manufacturer'])
                    ->select(DB::raw('MIN(p.price) AS min'))
                    ->get();
                return round($min[0]->min,2);
                break;
        }
    }

    public function getMaxPrice($level,$param){
        switch ($level){
            case 'search_str':
                $max = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.articles'),'LIKE',"%{$param['str']}%")
                    ->orWhere(DB::raw('p.name'),'LIKE',"%{$param['str']}%")
                    ->select(DB::raw(' MAX(p.price) AS max'))
                    ->get();
                return round($max[0]->max,2);
                break;
            case 'category':
                $max = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.linkageid'),(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('MAX(p.price) AS max'))
                    ->get();
                return round($max[0]->max,2);
                break;
            case 'pcode':
                $min = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_cross AS ac'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('ac.SupplierId'),DB::raw('sp.id'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'))
                    ->where(DB::raw('ac.OENbr'),$param['OENbr'])
                    ->where(DB::raw('ac.manufacturerId'),(int)$param['manufacturer'])
                    ->select(DB::raw('MAX(p.price) AS min'))
                    ->get();
                return round($min[0]->min,2);
                break;
            default:
                return [];
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

    public function getAttributes($level,$param){
        switch ($level){
            case 'search_str':
                return DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->orWhere([
                        [DB::raw('p.articles'),'LIKE',"%{$param['str']}%",'OR'],
                        [DB::raw('p.name'),'LIKE',"%{$param['str']}%",'OR']
                    ])
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('sp.matchcode'),DB::raw('p.brand'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'),function ($join){
                        $join->on(DB::raw('attr.DataSupplierArticleNumber'),'=',DB::raw('p.articles'));
                        $join->on(DB::raw('attr.supplierId'),'=',DB::raw('sp.id'));
                    })
                    ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                    ->get();
                break;
            default:
                return [];
        }
    }
}
