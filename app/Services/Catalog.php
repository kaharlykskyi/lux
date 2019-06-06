<?php

namespace App\Services;


use App\Product;
use Illuminate\Support\Facades\DB;

class Catalog
{
    public function getMinMaxPrice($level,$param){
        switch ($level){
            case 'search_str':
                $price = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.name'),'LIKE',"{$param['str']}%")
                    ->select(DB::raw(' MIN(p.price) AS min, MAX(p.price) AS max'))
                    ->get();
                return $price[0];
                break;
            case 'category':
                $price = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where($param['where'],(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('MIN(p.price) AS min, MAX(p.price) AS max'))
                    ->get();
                return $price[0];
                break;
            case 'modification':
                if ($param['type'] === 'passenger'){
                    $price = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                        ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                        ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                        ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                        ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                        ->where(DB::raw('al.linkagetypeid'),2)
                        ->select(DB::raw('MIN(p.price) AS min, MAX(p.price) AS max'))
                        ->get();
                } else {
                    $price = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                        ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                        ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                        ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                        ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                        ->where(DB::raw('al.linkagetypeid'),16)
                        ->select(DB::raw('MIN(p.price) AS min, MAX(p.price) AS max'))
                        ->get();
                }

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
                    ->where(DB::raw('p.name'),'LIKE',"{$param['str']}%")
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('sp.matchcode'),DB::raw('p.brand'))
                    ->select(DB::raw('sp.id AS supplierId, sp.description'))
                    ->distinct()
                    ->get();
                break;
            case 'category':
                return DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where($param['where'],(int)$param['id'])
                    ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                    ->select(DB::raw('s.id AS supplierId, s.description'))
                    ->distinct()
                    ->get();
                break;
            case 'modification':
                if ($param['type'] === 'passenger'){
                    $brands = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                        ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                        ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                        ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                        ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                        ->where(DB::raw('al.linkagetypeid'),2)
                        ->select(DB::raw('s.id AS supplierId, s.description'))
                        ->distinct()
                        ->get();
                } else {
                    $brands = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                        ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                        ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                        ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                        ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                        ->where(DB::raw('al.linkagetypeid'),16)
                        ->select(DB::raw('s.id AS supplierId, s.description'))
                        ->distinct()
                        ->get();
                }

                return $brands;
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
                case 'category':
                    $filters_data = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'),function ($query){
                            $query->on('al.DataSupplierArticleNumber','=','attr.DataSupplierArticleNumber');
                            $query->on('al.SupplierId','=','attr.supplierId');
                        })
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                        ->where($param['where'],(int)$param['id'])
                        ->where(DB::raw('al.linkagetypeid'),$param['type'] === 'passenger'?2:16)
                        ->whereIn('attr.id',$attr_ids)
                        ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                        ->distinct()
                        ->get();
                    break;

                case 'modification':
                    if ($param['type'] === 'passenger'){
                        $filters_data = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'),function ($query){
                                $query->on('al.DataSupplierArticleNumber','=','attr.DataSupplierArticleNumber');
                                $query->on('al.SupplierId','=','attr.supplierId');
                            })
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                            ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                            ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                            ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                            ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                            ->where(DB::raw('al.linkagetypeid'),2)
                            ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                            ->distinct()
                            ->get();
                    } else {
                        $filters_data = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'),function ($query){
                                $query->on('al.DataSupplierArticleNumber','=','attr.DataSupplierArticleNumber');
                                $query->on('al.SupplierId','=','attr.supplierId');
                            })
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                            ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                            ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                            ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                            ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                            ->where(DB::raw('al.linkagetypeid'),16)
                            ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                            ->distinct()
                            ->get();
                    }

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


    public function brandCatalog($article,$connection = 'mysql'){
        return DB::connection($connection)
            ->table('articles')
            ->join('suppliers','suppliers.id','=','articles.supplierId')
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),function ($query){
                $query->on('p.articles','=','articles.DataSupplierArticleNumber');
            })
            ->where('articles.DataSupplierArticleNumber',$article)
            ->orWhere('articles.FoundString',$article)
            ->select('p.articles','suppliers.id AS supplierId','p.brand'
                ,DB::raw('(SELECT p2.name FROM '
                    .config('database.connections.mysql.database').
                    '.products AS p2 WHERE p2.articles=p.articles AND p2.count > 0 GROUP BY p2.name HAVING MIN(p2.price) LIMIT 1) AS name'))
            ->distinct()
            ->get();
    }

    public function getCatalogProduct($article,$supplerId){
        $list_product_loc = Product::with('provider')
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_numbers'),'article_numbers.DataSupplierArticleNumber','=','products.articles')
            ->where('article_numbers.SupplierId',(int)$supplerId)
            ->where('article_numbers.DataSupplierArticleNumber',$article)
            ->select('products.*','article_numbers.SupplierId',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=products.articles AND a_img.SupplierId=article_numbers.SupplierId LIMIT 1) AS file'))
            ->orderBy('products.price')
            ->get();

        $buff_is = [];
        $list_product_loc_sort = [];
        foreach ($list_product_loc as $item){
            foreach ($list_product_loc as $val){
                if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                    $buff_is[] = $val->id;
                    $list_product_loc_sort[$item->articles][] = $val;
                }
            }
        }

        return $list_product_loc_sort;
    }

    public function replaceProducts($article,$supplerId,$connection = 'mysql'){
        $filter = [
            ['article_oe.DataSupplierArticleNumber','=',$article],
            ['article_oe.SupplierId','=',(int)$supplerId]
        ];

        $replace_product_loc = DB::connection($connection)
            ->table('article_oe')
            ->join('article_cross',function ($query){
                $query->on('article_cross.OENbr','=','article_oe.OENbr');
                $query->on('article_cross.manufacturerId','=','article_oe.manufacturerId');
            })
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),'p.articles','=','article_cross.PartsDataSupplierArticleNumber')
            ->join(DB::raw(config('database.connections.mysql.database') . '.providers'),'providers.id','=','p.provider_id')
            ->where($filter)
            ->where('p.articles','<>',$article)
            ->select('p.*','article_cross.SupplierId','providers.name AS provider_name',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=article_cross.SupplierId LIMIT 1) AS file'))
            ->orderBy('p.price')
            ->distinct()
            ->get();

        return $this->sortReplaceProduct($replace_product_loc);
    }

    public function getReplaceProductForOENrb($OENrb,$connection = 'mysql'){
        $replace_product_loc = DB::connection($connection)
            ->table('article_oe')
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),'p.articles','=','article_oe.datasupplierarticlenumber')
            ->join(DB::raw(config('database.connections.mysql.database') . '.providers'),'providers.id','=','p.provider_id')
            ->where('article_oe.OENbr',$OENrb)
            ->select('p.*','article_oe.supplierid AS SupplierId','providers.name AS provider_name',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=article_oe.supplierid LIMIT 1) AS file'))
            ->orderBy('p.price')
            ->distinct()
            ->get();

        return $this->sortReplaceProduct($replace_product_loc);
    }

    public function getQueryFilters($data){
        $resorv_word = [
            'max','min','supplier','type','modification_auto','type_auto','search_str','page'
        ];

        $query_attr = [];
        foreach ($data as $k => $val){
            if (!in_array($k,$resorv_word) && !empty($val)){
                $query_attr[$k] = $val;
            }
        }

        return $query_attr;
    }

    private function sortReplaceProduct($data){
        $buff_is = [];
        $replace_product_loc_sort = [];
        foreach ($data as $item){
            foreach ($data as $val){
                if ($item->articles === $val->articles && !in_array($val->id,$buff_is)){
                    $buff_is[] = $val->id;
                    $replace_product_loc_sort[$item->articles][] = $val;
                }
            }
        }

        return $replace_product_loc_sort;
    }
}
