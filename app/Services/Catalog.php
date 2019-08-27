<?php

namespace App\Services;


use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\{Cache, DB};

class Catalog
{
    public function getBrands($level,$param){
        switch ($level){
            case 'search_str':
                return Cache::remember('brands_search_str_'.(new Controller())->transliterateRU($param['str'],true), 60*24, function () use ($param) {
                    return DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                        ->where(DB::raw('p.name'),'LIKE',"{$param['str']}%")
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('sp.id'),DB::raw('p.brand'))
                        ->select(DB::raw('sp.id AS supplierId, sp.description'))
                        ->orderBy('sp.description, MAX(p.price) AS max')
                        ->groupBy('sp.description')
                        ->distinct()
                        ->get();
                });
                break;
            case 'category':
                $prd_id = [$param['category']->tecdoc_id];
                foreach ($param['category']->subCategory as $subCategory){
                    $prd_id[] = $subCategory->tecdoc_id;
                }

                return DB::connection('mysql_tecdoc')->table(DB::raw('article_links as al'))
                    ->join('suppliers AS sp',DB::raw('al.SupplierId'),DB::raw('sp.id'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                        $query->on(DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'));
                        $query->on('p.brand','=','al.SupplierId');
                    })
                    ->whereIn('al.productid',$prd_id)
                    ->where(DB::raw('al.linkagetypeid'),'=',2)
                    ->where('al.linkageid','=',(int)$param['car'])
                    ->select(DB::raw('sp.id AS supplierId, sp.description, MAX(p.price) AS max'))
                    ->groupBy('sp.description')
                    ->orderBy('sp.description')
                    ->distinct()
                    ->get();

                break;
            case 'modification':
                if ($param['type'] === 'passenger'){
                    $brands = Cache::remember('brands_modification_'.$param['linkageid'].$param['nodeid'], 60*24, function () use ($param) {
                        return DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                                $query->on('p.articles','=','al.DataSupplierArticleNumber');
                                $query->on('p.brand','=','al.supplierid');
                            })
                            ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                            ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                            ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                            ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                            ->where(DB::raw('al.linkagetypeid'),2)
                            ->select(DB::raw('s.id AS supplierId, s.description,MAX(p.price) AS max'))
                            ->groupBy('s.description')
                            ->orderBy('s.description')
                            ->distinct()
                            ->get();
                    });
                } else {
                    $brands = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                        ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                            $query->on('p.articles','=','al.DataSupplierArticleNumber');
                            $query->on('p.brand','=','al.supplierid');
                        })
                        ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                        ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                        ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                        ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                        ->where(DB::raw('al.linkagetypeid'),16)
                        ->select(DB::raw('s.id AS supplierId, s.description,MAX(p.price) AS max'))
                        ->groupBy('s.description')
                        ->orderBy('s.description')
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
                    ->select(DB::raw('sp.id AS supplierId, sp.description,MAX(p.price) AS max'))
                    ->distinct()
                    ->groupBy('sp.description')
                    ->orderBy('sp.description')
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
        if (isset($attr_ids) && !empty($attr_ids)){
            switch ($level){
                case 'category':
                    $prd_id = [$param['category']->tecdoc_id];
                    foreach ($param['category']->subCategory as $subCategory){
                        $prd_id[] = $subCategory->tecdoc_id;
                    }
                    $filters_data = DB::table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_attributes AS attr'))
                        ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'),function ($query){
                            $query->on('al.DataSupplierArticleNumber','=','attr.DataSupplierArticleNumber');
                            $query->on('al.SupplierId','=','attr.supplierId');
                        })
                        ->whereIn('al.productid',$prd_id)
                        ->where(DB::raw('al.linkagetypeid'),2)
                        ->where('al.linkageid','=',(int)$param['car'])
                        ->whereIn('attr.id',$attr_ids)
                        ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                        ->orderBy('attr.description')
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
                            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                                $query->on('p.articles','=','al.DataSupplierArticleNumber');
                                $query->on('p.brand','=','al.supplierid');
                            })
                            ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                            ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                            ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                            ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                            ->where(DB::raw('al.linkagetypeid'),2)
                            ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                            ->distinct()
                            ->orderBy('attr.description')
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
                            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                                $query->on('p.articles','=','al.DataSupplierArticleNumber');
                                $query->on('p.brand','=','al.supplierid');
                            })
                            ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                            ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                            ->where(DB::raw("al.linkageid"),(int)$param['linkageid'])
                            ->where(DB::raw("pds.nodeid"),(int)$param['nodeid'])
                            ->where(DB::raw('al.linkagetypeid'),16)
                            ->select(DB::raw('attr.id, attr.description, attr.displaytitle, attr.displayvalue'))
                            ->distinct()
                            ->orderBy('attr.description')
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
                $query->on('p.brand','=','suppliers.id');
            })
            ->where('articles.DataSupplierArticleNumber',$article)
            ->orWhere('articles.FoundString',$article)
            ->select('p.articles','suppliers.id AS supplierId','suppliers.description AS brand'
                ,DB::raw('(SELECT p2.name FROM '
                    .config('database.connections.mysql.database').
                    '.products AS p2 WHERE p2.articles=p.articles AND p2.brand=suppliers.id AND p2.count > 0 GROUP BY p2.name HAVING MIN(p2.price) LIMIT 1) AS name'))
            ->distinct()
            ->get();
    }

    public function getCatalogProduct($article,$supplerId){
        $list_product_loc = Product::with('provider')
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers sp'),'sp.id','=','products.brand')
            ->where('sp.id',(int)$supplerId)
            ->where('products.articles',$article)
            ->select('products.*','sp.id AS SupplierId','sp.description AS brand',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=products.articles AND a_img.SupplierId=sp.id LIMIT 1) AS file'))
            ->orderBy('products.price')
            ->get();

        return $this->sortProduct($list_product_loc);
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
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),function($query){
                $query->on('p.articles','=','article_cross.PartsDataSupplierArticleNumber');
                $query->on('p.brand','=','article_cross.SupplierId');
            })
            ->join(DB::raw(config('database.connections.mysql.database') . '.providers'),'providers.id','=','p.provider_id')
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers sp'),'sp.id','=','p.brand')
            ->where($filter)
            ->where('p.articles','<>',$article)
            ->select('p.*','article_cross.SupplierId','providers.name AS provider_name','sp.description AS brand',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=p.brand LIMIT 1) AS file'))
            ->orderBy('p.price')
            ->distinct()
            ->get();

        return $this->sortProduct($replace_product_loc);
    }

    public function getProductForOENbr($OENrb){
        $list_product_loc = Product::with('provider')
            ->where('products.articles',$OENrb)
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.manufacturers m'),'m.id','=','products.brand')
            ->select('products.*','m.description AS brand')
            ->orderBy('products.price')
            ->get();

        return $this->sortProduct($list_product_loc);
    }

    public function getReplaceProductForOENrb($OENrb,$manufacturerId,$connection = 'mysql'){
        $replace_product_loc = DB::connection($connection)
            ->table('article_cross')
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),function($query){
                $query->on('p.articles','=','article_cross.PartsDataSupplierArticleNumber');
                $query->on('p.brand','=','article_cross.SupplierId');
            })
            ->join('suppliers','suppliers.id','=','p.brand')
            ->join(DB::raw(config('database.connections.mysql.database') . '.providers'),'providers.id','=','p.provider_id')
            ->where('article_cross.OENbr',$OENrb)
            ->where('article_cross.manufacturerId',(int)$manufacturerId)
            ->select('p.*','article_cross.SupplierId','providers.name AS provider_name','suppliers.description AS brand',
                DB::raw('(SELECT a_img.PictureName 
                            FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=p.brand LIMIT 1) AS file'))
            ->orderBy('p.price')
            ->distinct()
            ->get();

        return $this->sortProduct($replace_product_loc);
    }

    public function getManufacturerForOENbr($OENrb,$connection = 'mysql'){
        $manufacturers = DB::connection($connection)
            ->table('article_oe')
            ->join('manufacturers','manufacturers.id','=','article_oe.manufacturerId')
            ->where('article_oe.OENbr',$OENrb)
            ->select('manufacturers.id AS supplierId','manufacturers.matchcode','article_oe.OENbr AS articles')
            ->distinct()
            ->get();

        return $manufacturers;
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

    private function sortProduct($data){
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

    public function getArtFile($data,$connection = 'mysql'){
        $filters = null;
        $files = null;

        foreach ($data as $item){
            if (isset($item->supplierId) && isset($item->articles)){
                if (isset($filters)){
                    $filters .= " OR (SupplierId={$item->supplierId} AND DataSupplierArticleNumber='{$item->articles}')";
                }else{
                    $filters = " (SupplierId={$item->supplierId} AND DataSupplierArticleNumber='{$item->articles}')";
                }
            }
        }

        if (isset($filters)){
            $files = DB::connection($connection)
                ->table('article_images')
                ->whereRaw($filters)
                ->select('SupplierId','DataSupplierArticleNumber','PictureName')
                ->groupBy('DataSupplierArticleNumber')
                ->get();
        }

        return $files;
    }
}
