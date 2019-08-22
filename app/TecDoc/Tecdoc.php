<?php
namespace App\TecDoc;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Tecdoc
{
    /**
     * The connection name for the model.
     * @var
     */
    public $connection;

    /**
     * Type auto
     * @var [passenger|commercial|motorbike|engine|axle]
     */
    public $type;

    /**
     * Tecdoc constructor.
     * @param $connection
     */
    public function __construct($connection = 'mysql')
    {
        $this->connection = $connection;
        $this->setType('passenger');
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * (1) АВТОМОБИЛИ
     * (1.1) Марки авто (производители)
     *
     * @return mixed
     */
    public function getBrands()
    {
        switch ($this->type) {
            case 'passenger':
                $where = " AND ispassengercar = 'True'";
                break;
            case 'commercial':
                $where = " AND iscommercialvehicle = 'True'";
                break;
            case 'motorbike':
                $where = " AND ismotorbike  = 'True' AND haslink = 'True'";
                break;
            case 'engine':
                $where = " AND isengine = 'True'";
                break;
            case 'axle':
                $where = " AND isaxle = 'True'";
                break;
            default:
                $where = " AND ispassengercar = 'True'";
        }

        $order = $this->type == 'motorbike' ? 'description' : 'matchcode';

        return cache()->remember('all_brands', 60*24, function () use ($where,$order) {
            return DB::connection($this->connection)->select("
            SELECT id, description,matchcode
            FROM manufacturers
            WHERE canbedisplayed = 'True' " . $where . "
            ORDER BY " . $order
            );
        });
    }

    /**
     * (1.2) Модели авто
     *
     * @param $brand_id
     * @param $type
     * @param null $pattern
     * @return mixed
     */
    public function getModels($brand_id, $pattern = null,$limit = null)
    {
        switch ($this->type) {
            case 'passenger':
                $where = " AND ispassengercar = 'True'";
                break;
            case 'commercial':
                $where = " AND iscommercialvehicle = 'True'";
                break;
            case 'motorbike':
                $where = " AND ismotorbike  = 'True'";
                break;
            case 'engine':
                $where = " AND isengine = 'True'";
                break;
            case 'axle':
                $where = " AND isaxle = 'True'";
                break;
        }

        if ($pattern != null) $where .= " AND constructioninterval LIKE '%" . $pattern . "%'";

        $limit_str = isset($limit)? " LIMIT {$limit}":'';

        return DB::connection($this->connection)->select("
            SELECT id, description name, constructioninterval,fulldescription
            FROM models
            WHERE canbedisplayed = 'True'
            AND manufacturerid = " . (int)$brand_id . " " . $where . "
            ORDER BY description {$limit_str}
        ");
    }

    /**
     * (1.3) Модификации авто
     *
     * @param $model_id
     * @param array $params -> [col - operator - val]
     * @return mixed
     */
    public function getModifications($model_id, array $params = null)
    {
        $where = '';
        if (isset($params)){
            foreach ($params as $param){
                $where .= " AND {$param[0]} {$param[1]} {$param[2]}";
            }
        }

        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM passanger_cars pc 
					LEFT JOIN passanger_car_attributes a on pc.id = a.passangercarid
					WHERE canbedisplayed = 'True'
					AND modelid = " . (int)$model_id . " AND ispassengercar = 'True' {$where}");
                break;
            case 'commercial':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM commercial_vehicles cv 
					LEFT JOIN commercial_vehicle_attributes a on cv.id = a.commercialvehicleid
					WHERE canbedisplayed = 'True'
					AND modelid = " . (int)$model_id . " AND iscommercialvehicle = 'True' {$where}");
                break;
            case 'motorbike':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM motorbikes m 
					LEFT JOIN motorbike_attributes a on m.id = a.motorbikeid
					WHERE canbedisplayed = 'True'
					AND modelid = " . (int)$model_id . " AND ismotorbike = 'True'");
                break;
            case 'engine':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, salesDescription, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM engines e 
					LEFT JOIN engine_attributes a on e.id= a.engineid
					WHERE canbedisplayed = 'True'
					AND manufacturerId = " . (int)$model_id . " AND isengine = 'True' {$where}");
                break;
            case 'axle':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM axles ax 
					LEFT JOIN axle_attributes a on ax.id= a.axleid
					WHERE canbedisplayed = 'True'
					AND modelid = " . (int)$model_id . " AND isaxle = 'True'");
                break;
        }
    }

    /**
     * (1.4) Марка по ID
     *
     * @param $id
     * @return mixed
     */
    public function getBrandById($id)
    {
        switch ($this->type) {
            case 'passenger':
                $where = " AND ispassengercar = 'True'";
                break;
            case 'commercial':
                $where = " AND iscommercialvehicle = 'True'";
                break;
            case 'motorbike':
                $where = " AND ismotorbike = 'True' AND haslink = 'True'";
                break;
            case 'engine':
                $where = " AND isengine = 'True'";
                break;
            case 'axle':
                $where = " AND isaxle = 'True'";
                break;
        }
        return DB::connection($this->connection)->select("
            SELECT id, description name
            FROM manufacturers
            WHERE canbedisplayed = 'True' " . $where . " AND id = " . (int)$id . ";
        ");
    }

    public function getAllSuppliers(){
        return cache()->remember('all_suppliers', 60*24*7, function () {
            return DB::connection($this->connection)->select("SELECT DISTINCT id,description,matchcode FROM suppliers");
        });
    }

    /**
     * (1.5) Модель по ID
     *
     * @param $id
     * @return mixed
     */
    public function getModelById($id)
    {
        switch ($this->type) {
            case 'passenger':
                $where = " AND ispassengercar = 'True'";
                break;
            case 'commercial':
                $where = " AND iscommercialvehicle = 'True'";
                break;
            case 'motorbike':
                $where = " AND ismotorbike = 'True'";
                break;
            case 'engine':
                $where = " AND isengine = 'True'";
                break;
            case 'axle':
                $where = " AND isaxle = 'True'";
                break;
        }

        return DB::connection($this->connection)->select("
            SELECT id, description name, constructioninterval, manufacturerid
            FROM models
            WHERE canbedisplayed = 'True' " . $where . " AND id = " . (int)$id . "
        ");
    }

    /**
     * (1.6) Модификация по ID
     *
     * @param $id
     * @return mixed
     */
    public function getModificationById($id)
    {
        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue, pc.modelid
					FROM passanger_cars pc 
					LEFT JOIN passanger_car_attributes a on pc.id = a.passangercarid
					WHERE canbedisplayed = 'True'
					AND id = " . (int)$id . " AND ispassengercar = 'True'");
                break;
            case 'commercial':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM commercial_vehicles cv 
					LEFT JOIN commercial_vehicle_attributes a on cv.id = a.commercialvehicleid
					WHERE canbedisplayed = 'True'
					AND id = " . (int)$id . " AND iscommercialvehicle = 'True'");
                break;
            case 'motorbike':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM motorbikes m 
					LEFT JOIN motorbike_attributes a on m.id = a.motorbikeid
					WHERE canbedisplayed = 'True'
					AND id = " . (int)$id . " AND ismotorbike = 'True'");
                break;
            case 'engine':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, salesDescription, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM engines e 
					LEFT JOIN engine_attributes a on e.id = a.engineid
					WHERE canbedisplayed = 'True'
					AND id = " . (int)$id . " AND isengine = 'True'");
                break;
            case 'axle':
                return DB::connection($this->connection)->select("
					SELECT id, fulldescription name, a.attributegroup, a.attributetype, a.displaytitle, a.displayvalue
					FROM axles ax 
					LEFT JOIN axle_attributes a on ax.id = a.axleid
					WHERE canbedisplayed = 'True'
					AND id = " . (int)$id . " AND isaxle = 'True'");
                break;
        }
    }

    /**
     * (2) Дерево категорий / разделы
     * (2.1) Построение дерева категорий изделий для заданного типа автомобиля (от родительского)
     *
     * Последовательно устанавливая следующие значения parentid, можно получить ещё 4 уровня дерева
     * Если есть, то ее parentid ставим на вход метода
     *
     * @param $modification_id
     * @param int $parent
     * @param null $limit
     * @param bool $count_product
     * @return mixed
     */
    public function getSections($modification_id, $parent = 0,$limit = null,$count_product = false)
    {
        $limit_select = isset($limit)?" LIMIT {$limit}":'';
        $select_count_product = '';

        if ($count_product){
            $select_count_product = ",(select COUNT(DISTINCT p.articles) 
                    from td1q2018.article_links AS al inner 
                    join td1q2018.passanger_car_pds AS pds on al.supplierid = pds.supplierid 
                    inner join td1q2018.suppliers AS s on s.id = al.supplierid 
                    inner join td1q2018.passanger_car_prd AS prd on prd.id = al.productid 
                    inner join lux.products AS p on p.articles = al.DataSupplierArticleNumber  AND p.brand = al.supplierid
                    where al.productid = pds.productid and al.linkageid = pds.passangercarid and al.linkageid = ".(int)$modification_id." and pds.nodeid = passanger_car_trees.id and al.linkagetypeid = 2 AND p.count > 0) AS count_product ";
        }


        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)->select("
						SELECT id, description,parentid {$select_count_product}
						FROM passanger_car_trees  WHERE passangercarid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
						ORDER BY description {$limit_select}
					");
                break;
            case 'commercial':
                return DB::connection($this->connection)->select("
						SELECT id, description
						FROM commercial_vehicle_trees WHERE commercialvehicleid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
						ORDER BY description {{$limit_select}}
					");
                break;
            case 'motorbike':
                return DB::connection($this->connection)->select("
						SELECT id, description
						FROM motorbike_trees WHERE motorbikeid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
						ORDER BY description
					");
                break;
            case 'engine':
                return DB::connection($this->connection)->select("
						SELECT id, description
						FROM engine_trees WHERE engineid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
						ORDER BY description
					");
                break;
            case 'axle':
                return DB::connection($this->connection)->select("
						SELECT id, description
						FROM axle_trees WHERE axleid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
						ORDER BY description
					");
                break;
        }
    }

    /**
     * (2.2) Название раздела по ID - используется в СЕО
     *
     * @param $section_id
     * @return mixed
     */
    public function getSectionName($section_id)
    {
        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)->select("SELECT * FROM passanger_car_trees WHERE id=" . (int)$section_id . " LIMIT 1");
                break;
            case 'commercial':
                return DB::connection($this->connection)->select("SELECT * FROM commercial_vehicle_trees WHERE id=" . (int)$section_id . " LIMIT 1");
                break;
            case 'motorbike':
                return DB::connection($this->connection)->select("SELECT * FROM motorbike_trees WHERE id=" . (int)$section_id . " LIMIT 1");
                break;
            case 'engine':
                return DB::connection($this->connection)->select("SELECT * FROM engine_trees WHERE id=" . (int)$section_id . " LIMIT 1");
                break;
            case 'axle':
                return DB::connection($this->connection)->select("SELECT * FROM axle_trees WHERE id=" . (int)$section_id . " LIMIT 1");
                break;
        }
    }

    /**
     * (2.3) Поиск запчастей раздела
     *
     * @param $modification_id
     * @param $section_id
     * @param int $pre
     * @param array $filter
     * @param $save_attr
     * @param $query_attr
     * @param string $sort
     * @return mixed
     */
    public function getSectionParts($modification_id, $section_id,$pre,array $filter,$save_attr, $query_attr,$sort = 'ASC')
    {
        $attr_filter = $this->getSortAttr($save_attr,$query_attr);

        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)
                    ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                        $query->on('p.articles','=','al.DataSupplierArticleNumber');
                        $query->on('p.brand','=','al.supplierid');
                    })
                    ->leftJoin('article_attributes as attr',function ($query){
                        $query->on('attr.DataSupplierArticleNumber','=','al.DataSupplierArticleNumber');
                        $query->on('attr.supplierId','=','al.SupplierId');
                    })
                    ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                    ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                    ->where(DB::raw("al.linkageid"),(int)$modification_id)
                    ->where(DB::raw("pds.nodeid"),(int)$section_id)
                    ->where(DB::raw('al.linkagetypeid'),2)
                    ->where([
                        [DB::raw('p.price'),'>=',$filter['price']['min']],
                        [DB::raw('p.price'),'<=',$filter['price']['max']]
                    ])
                    ->where(function ($query) use ($attr_filter) {
                        foreach ($attr_filter as $item){
                            $group_attr = [];

                            foreach ($item as  $data){
                                $group_attr[] = $data;
                            }

                            $query->where($group_attr,null,null,'OR');
                        }
                    })
                    ->where('p.count','>',0)
                    ->whereRaw(isset($filter['supplier'])? " s.id IN (".implode(',',$filter['supplier']).")":'s.id > 0')
                    ->select(DB::raw('p.articles, s.description matchcode,al.supplierid supplierId,p.id,p.name,p.price,p.count,
                            (SELECT a_img.PictureName 
                            FROM article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=al.supplierid LIMIT 1) AS file'))
                    ->orderBy(DB::raw('p.price'),$sort)
                    ->groupBy('p.articles')
                    ->havingRaw('MIN(p.price)')
                    ->distinct()
                    ->paginate($pre,['p.id']);
                break;
            case 'commercial':
                return DB::connection($this->connection)
                    ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                        $query->on('p.articles','=','al.DataSupplierArticleNumber');
                        $query->on('p.brand','=','al.supplierid');
                    })
                    ->leftJoin('article_attributes as attr',function ($query){
                        $query->on('attr.DataSupplierArticleNumber','=','al.DataSupplierArticleNumber');
                        $query->on('attr.supplierId','=','al.SupplierId');
                    })
                    ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                    ->where(DB::raw('al.linkageid'),DB::raw('pds.commertialvehicleid'))
                    ->where(DB::raw('al.linkageid'),(int)$modification_id)
                    ->where(DB::raw('pds.nodeid'),(int)$section_id)
                    ->where(DB::raw('al.linkagetypeid'),16)
                    ->where([
                        [DB::raw('p.price'),'>=',$filter['price']['min']],
                        [DB::raw('p.price'),'<=',$filter['price']['max']]
                    ])
                    ->where(function ($query) use ($attr_filter) {
                        foreach ($attr_filter as $item){
                            $group_attr = [];

                            foreach ($item as  $data){
                                $group_attr[] = $data;
                            }

                            $query->where($group_attr,null,null,'OR');
                        }
                    })
                    ->where('p.count','>',0)
                    ->whereRaw(isset($filter['supplier'])? " s.id IN (".implode(',',$filter['supplier']).")":'s.id > 0')
                    ->select(DB::raw('p.articles, s.description matchcode,al.supplierid supplierId,p.id,p.name,p.price,p.count,
                            (SELECT a_img.PictureName 
                            FROM article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=al.supplierid LIMIT 1) AS file'))
                    ->orderBy(DB::raw('p.price'),$sort)
                    ->groupBy('p.articles')
                    ->havingRaw('MIN(p.price)')
                    ->distinct()
                    ->paginate($pre,['p.id']);
                break;
            case 'motorbike':
                return DB::connection($this->connection)->select(" SELECT al.datasupplierarticlenumber part_number, s.description supplier_name, prd.description product_name
                    FROM article_links al 
                    JOIN motorbike_pds pds on al.supplierid = pds.supplierid
                    JOIN suppliers s on s.id = al.supplierid
                    JOIN motorbike_prd prd on prd.id = al.productid
                    WHERE al.productid = pds.productid
                    AND al.linkageid = pds.motorbikeid
                    AND al.linkageid = " . (int)$modification_id . "
                    AND pds.nodeid = " . (int)$section_id . "
                    AND al.linkagetypeid = 777
                    ORDER BY s.description, al.datasupplierarticlenumber");
                break;
            case 'engine':
                return DB::connection($this->connection)->select(" SELECT pds.engineid, al.datasupplierarticlenumber part_number, prd.description product_name, s.description supplier_name
                    FROM article_links al 
                    JOIN engine_pds pds on al.supplierid = pds.supplierid
                    JOIN suppliers s on s.id = al.supplierid
                    JOIN engine_prd prd on prd.id = al.productid
                    WHERE al.productid = pds.productid
                    AND al.linkageid = pds.engineid
                    AND al.linkageid = " . (int)$modification_id . "
                    AND pds.nodeid = " . (int)$section_id . "
                    AND al.linkagetypeid = 14
                    ORDER BY s.description, al.datasupplierarticlenumber");
                break;
            case 'axle':
                return DB::connection($this->connection)->select(" SELECT pds.axleid, al.datasupplierarticlenumber part_number, prd.description product_name, s.description supplier_name
                    FROM article_links al 
                    JOIN axle_pds pds on al.supplierid = pds.supplierid
                    JOIN suppliers s on s.id = al.supplierid
                    JOIN axle_prd prd on prd.id = al.productid
                    WHERE al.productid = pds.productid
                    AND al.linkageid = pds.axleid
                    AND al.linkageid = " . (int)$modification_id . "
                    AND pds.nodeid = " . (int)$section_id . "
                    AND al.linkagetypeid = 19
                    ORDER BY s.description, al.datasupplierarticlenumber");
                break;

        }
    }

    /**
     * (3) Информация об изделии
     * (3.1) Оригинальные номера
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getOemNumbers($number, $brand_id)
    {
        return DB::connection($this->connection)->select("
            SELECT DISTINCT a.OENbr FROM article_oe a 
            WHERE a.datasupplierarticlenumber='" . $number . "' AND a.manufacturerId='" . $brand_id . "'
            ORDER BY a.OENbr
        ");
    }

    public function getManufacturerForOed(array $params,$alies_manfactias){

        if (isset($params['OENbr'])){
            return DB::connection($this->connection)
                ->table('manufacturers as m')
                ->join('article_oe as oe','oe.manufacturerId','=','m.id')
                ->where('oe.OENbr',$params['OENbr'])
                ->select(DB::raw('m.id, m.matchcode, m.description'))
                ->distinct()
                ->first();
        }elseif(isset($params['trademark'])) {
            $str = isset($alies_manfactias[$params['trademark']])?$alies_manfactias[$params['trademark']]:$params['trademark'];
            return DB::connection($this->connection)
                ->table('manufacturers as m')
                ->join('article_oe as oe','oe.manufacturerId','=','m.id')
                ->where('m.matchcode',$str)
                ->orWhere('m.description',$str)
                ->select(DB::raw('m.id, m.matchcode, m.description'))
                ->distinct()
                ->first();
        }else{
            return null;
        }
    }

    /**
     * (3.2) Статус изделия
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtStatus($number, $brand_id)
    {
        return DB::connection($this->connection)->select("
            SELECT NormalizedDescription, ArticleStateDisplayValue FROM articles WHERE DataSupplierArticleNumber='" . $number . "' AND supplierId='" . $brand_id . "'
        ");
    }

    /**
     * (3.3) Характеристики изделия
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtAttributes($number, $brand_id)
    {
        $band_filter = '';
        if (isset($brand_id)){
            $band_filter = ' AND supplierId=' . (int)$brand_id;
        }

        return DB::connection($this->connection)->select("
            SELECT  * FROM article_attributes WHERE datasupplierarticlenumber='" . $number . "'" . $band_filter);
    }

    /**
     * (3.4) Файлы изделия
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtFiles($number, $brand_id)
    {
        $band_filter = '';
        if (isset($brand_id)){
            $band_filter = ' AND SupplierId=' . (int)$brand_id;
        }

        return DB::connection($this->connection)->select("
            SELECT Description, PictureName FROM article_images WHERE DataSupplierArticleNumber='" . $number . "'" . $band_filter);
    }

    /**
     * (3.5) Применимость изделия
     *
     * @param $number
     * @param $brand_id
     * @return array
     */
    public function getArtVehicles($number, $brand_id)
    {
        $result = [];
        $model_id = [];
        $rows = DB::connection($this->connection)
            ->table('article_li')
            ->where('DataSupplierArticleNumber',$number)
            ->where('supplierId',(int)$brand_id)
            ->select('linkageTypeId','linkageId')->get();

        foreach ($rows as $row){
            if ($row->linkageTypeId === 'PassengerCar'){
                $model_id['PassengerCar'][] = $row->linkageId;
            }
            if ($row->linkageTypeId === 'CommercialVehicle'){
                $model_id['CommercialVehicle'][] = $row->linkageId;
            }
        }

        foreach ($model_id as $k => $row) {
            switch ($k) {
                case 'PassengerCar':
                    $result['PassengerCar'] = DB::connection($this->connection)
                        ->table('passanger_cars AS p')
                        ->join('models AS m','m.id','=','p.modelid')
                        ->join('manufacturers AS mm','mm.id','=','m.manufacturerid')
                        ->whereIn('p.id',$row)
                        ->select('p.id','mm.description AS make','m.description AS model','p.constructioninterval','p.description')
                        ->distinct()
                        ->get();
                    break;
                case 'CommercialVehicle':
                    $result['CommercialVehicle'] = DB::connection($this->connection)
                        ->table('commercial_vehicles AS p')
                        ->join('models AS m','m.id','=','p.modelid')
                        ->join('manufacturers AS mm','mm.id','=','m.manufacturerid')
                        ->whereIn('p.id',$row)
                        ->select('p.id','mm.description AS make','m.description AS model','p.constructioninterval','p.description')
                        ->distinct()
                        ->get();
                    break;
                case 'Motorbike':
                    $result['Motorbike'] = DB::connection($this->connection)
                        ->table('motorbikes AS p')
                        ->join('models AS m','m.id','=','p.modelid')
                        ->join('manufacturers AS mm','mm.id','=','m.manufacturerid')
                        ->whereIn('p.id',$row)
                        ->select('p.id','mm.description AS make','m.description AS model','p.constructioninterval','p.description')
                        ->distinct()
                        ->get();
                    break;
                case 'Engine':
                    $result['Engine'] = DB::connection($this->connection)
                        ->table('engines AS p')
                        ->join('models AS m','m.id','=','p.modelid')
                        ->join('manufacturers AS mm','mm.id','=','m.manufacturerid')
                        ->whereIn('p.id',$row)
                        ->select('p.id','mm.description AS make','m.description AS model','p.constructioninterval','p.description')
                        ->distinct()
                        ->get();
                    break;
                case 'Axle':
                    $result['Axle'] = DB::connection($this->connection)
                        ->table('axles AS p')
                        ->join('models AS m','m.id','=','p.modelid')
                        ->join('manufacturers AS mm','mm.id','=','m.manufacturerid')
                        ->whereIn('p.id',$row)
                        ->select('p.id','mm.description AS make','m.description AS model','p.constructioninterval','p.description')
                        ->distinct()
                        ->get();
                    break;
            }
        }
        return $result;
    }

    /**
     * (3.6) Замены изделия
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtReplace($number, $brand_id)
    {
        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_rn as ar'))
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ar.replacedatasupplierarticlenumber'))
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_images as img'),function ($join){
                $join->on(DB::raw('img.DataSupplierArticleNumber'),'=',DB::raw('ar.replacedatasupplierarticlenumber'));
                $join->on(DB::raw('img.SupplierId'),'=',DB::raw('ar.replacedupplierid'));
            })
            ->where(DB::raw('ar.DataSupplierArticleNumber'),$number)
            ->select(DB::raw('ar.replacedupplierid AS supplierId,img.PictureName, ar.replacedatasupplierarticlenumber AS DataSupplierArticleNumber, p.brand matchcode, p.id, p.name, p.price,p.old_price,p.count'))
            ->get();
    }

    /**
     * (3.7) Аналоги-заменители
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtCross($number, $brand_id)
    {
        return DB::connection($this->connection)
            ->table('article_oe AS a')
            ->join('article_cross as c',function ($query){
                $query->on('c.OENbr','=','a.OENbr');
                $query->on('c.manufacturerId','=','a.manufacturerId');
            })
            ->join('suppliers as s','s.id','=','c.SupplierId')
            ->join(DB::raw(config('database.connections.mysql.database') . '.products AS p'),function ($query){
                $query->on('p.articles','=','c.PartsDataSupplierArticleNumber');
                $query->on('p.brand','=','c.SupplierId');
            })
            ->where('a.datasupplierarticlenumber',$number)
            ->where('a.SupplierId',(int)$brand_id)
            ->where('p.articles','<>',$number)
            ->where('p.count','>',0)
            ->select('p.*','s.id as supplierId','s.matchcode',
                DB::raw("(SELECT a_img.PictureName 
                    FROM article_images AS a_img 
                    WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=s.id LIMIT 1) AS file"))
            ->groupBy('p.articles')
            ->havingRaw('MIN(p.price)')
            ->distinct()
            ->get();
    }

    public function getAccessories($number)
    {
        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_acc as acc'))
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                $query->on('p.articles','acc.AccDataSupplierArticleNumber');
                $query->on('p.brand','acc.AccSupplierId');
            })
            ->where(DB::raw('acc.DataSupplierArticleNumber'),$number)
            ->select(DB::raw('acc.AccSupplierId AS supplierId, acc.AccDataSupplierArticleNumber DataSupplierArticleNumber, p.brand matchcode, p.id, p.name, p.price,p.old_price,p.count,
                    (SELECT a_img.PictureName 
                    FROM article_images AS a_img 
                    WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=acc.AccSupplierId LIMIT 1) AS file'))
            ->get();
    }

    /**
     * (3.8) Комплектующие (части) изделия
     *
     * @param $number
     * @param $brand_id
     * @return mixed
     */
    public function getArtParts($number, $brand_id)
    {
        return DB::connection($this->connection)->select("
            SELECT DISTINCT description Brand, Quantity, PartsDataSupplierArticleNumber FROM article_parts 
            JOIN suppliers ON id=PartsSupplierId
            WHERE DataSupplierArticleNumber='" . $number . "' AND supplierId='" . $brand_id . "'
        ");
    }

    /**
     * Get category
     * @param array|null $param
     * @return array
     * @throws \Exception
     */
    public function getCategory(array $param = null){
        $where = '';
        if (isset($param)){
            $select = "";
            foreach ($param[0] as $k => $item){
                if (count($param[0]) !== ($k + 1)){
                    $select .= " {$item},";
                } else {
                    $select .= " {$item}";
                }
            }
            $where = ' WHERE ';
            foreach ($param[1] as $k => $item){
                if (count($param[0]) !== ($k + 1)){
                    if ( $k === 0){
                        $where .= " {$item[0]}{$item[1]}{$item[2]}";
                    }else{
                        $where .= " {$item[0]}{$item[1]}{$item[2]} AND";
                    }
                } else {
                    $where .= " {$item[0]}{$item[1]}{$item[2]}";
                }
            }
        } else {
            $select = '*';
        }

        switch ($this->type){
            case 'passenger':
                return cache()->remember('subcategory' . isset($param[1][0][2])?str_replace(' ','_',(new Controller())->transliterateRU($param[1][0][2],true)):'', 60*24*7, function () use ($where, $select) {
                    return DB::connection($this->connection)
                        ->select("SELECT DISTINCT {$select} FROM `passanger_car_prd` AS pcr {$where}");
                });

                break;
            case 'commercial':
                return cache()->remember('subcategory' . isset($param[1][0][2])?str_replace(' ','_',(new Controller())->transliterateRU($param[1][0][2],true)):'', 60*24*7, function () use ($where, $select) {
                    return DB::connection($this->connection)
                        ->select("SELECT DISTINCT {$select} FROM `commercial_vehicle_prd` {$where}");
                });
                break;
        }
    }

    public function getCategoryProduct($category,$linkageid,$pre,array $filter,$save_attr, $query_attr,$sort = 'ASC'){
        if (isset($filter['supplier'])){
            foreach ($filter['supplier'] as $k => $item){
                $filter['supplier'][$k] = (int)$item;
            }
        }

        $attr_filter = $this->getSortAttr($save_attr,$query_attr);

        $prd_id = [$category->tecdoc_id];
        foreach ($category->subCategory as $subCategory){
            $prd_id[] = $subCategory->tecdoc_id;
        }

        if (!isset($attr_filter[1])){
            return DB::connection($this->connection)
                ->table(DB::raw('article_links as al'))
                ->join('suppliers AS sp',DB::raw('al.SupplierId'),DB::raw('sp.id'))
                ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                    $query->on(DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'));
                    $query->on('p.brand','=','al.SupplierId');
                })
                ->whereIn('al.productid',$prd_id)
                ->where('al.linkagetypeid','=',2)
                ->where('al.linkageid','=',$linkageid)
                ->where([
                    [DB::raw('p.price'),'>=',$filter['price']['min']],
                    [DB::raw('p.price'),'<=',$filter['price']['max']]
                ])
                ->where('p.count','>',0)
                ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
                ->select(DB::raw('sp.id AS supplierId, p.articles,sp.description AS matchcode, p.id, p.name, p.price,p.count'))
                ->orderBy('p.price',$sort)
                ->groupBy('p.articles')
                ->havingRaw('MIN(p.price)')
                ->distinct()
                ->paginate((int)$pre,['p.id']);
        } else{
            return DB::connection($this->connection)
                ->table(DB::raw('article_links as al'))
                ->join('suppliers AS sp',DB::raw('al.SupplierId'),DB::raw('sp.id'))
                ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                    $query->on(DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'));
                    $query->on('p.brand','=','al.SupplierId');
                })
                ->leftJoin('article_attributes as attr',function ($query){
                    $query->on('attr.DataSupplierArticleNumber','=','al.DataSupplierArticleNumber');
                    $query->on('attr.supplierId','=','al.SupplierId');
                })
                ->whereIn('al.linkageid',$prd_id)
                ->where('al.linkagetypeid','=',2)
                ->where('al.linkageid','=',$linkageid)
                ->where([
                    [DB::raw('p.price'),'>=',$filter['price']['min']],
                    [DB::raw('p.price'),'<=',$filter['price']['max']]
                ])
                ->where(function ($query) use ($attr_filter) {
                    foreach ($attr_filter as $item){
                        $group_attr = [];

                        foreach ($item as  $data){
                            $group_attr[] = $data;
                        }

                        $query->where($group_attr,null,null,'OR');
                    }
                })
                ->where('p.count','>',0)
                ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
                ->select(DB::raw('al.SupplierId AS supplierId, p.articles,sp.description AS matchcode, p.id, p.name, p.price,p.count'))
                ->orderBy('p.price',$sort)
                ->groupBy('p.articles')
                ->havingRaw('MIN(p.price)')
                ->distinct()
                ->paginate((int)$pre,['p.id']);
        }
    }

    public function getProductForArticleOE($OENbr,$manufacturer_id,$pre,array $filter,$sort = 'ASC'){

        if (isset($filter['supplier'])){
            foreach ($filter['supplier'] as $k => $item){
                $filter['supplier'][$k] = (int)$item;
            }
        }

        return DB::connection($this->connection)
            ->table('article_cross AS ac')
            ->join('suppliers AS sp',DB::raw('ac.SupplierId'),DB::raw('sp.id'))
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),function ($query){
                $query->on(DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'));
                $query->on('p.brand','ac.SupplierId');
            })
            ->where(DB::raw('ac.OENbr'),$OENbr)
            ->where(DB::raw('ac.manufacturerId'),(int)$manufacturer_id)
            ->where([
                [DB::raw('p.price'),'>=',$filter['price']['min']],
                [DB::raw('p.price'),'<=',$filter['price']['max']]
            ])
            ->where('p.count','>',0)
            ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
            ->select(DB::raw('sp.id AS supplierId, p.articles, sp.matchcode, p.id, p.name,p.price AS price,p.count,
                (SELECT a_img.PictureName 
                            FROM article_images AS a_img 
                            WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=sp.id LIMIT 1) AS file'))
            ->orderBy(DB::raw('p.price'),$sort)
            ->groupBy('p.articles')
            ->havingRaw('MIN(p.price)')
            ->distinct()
            ->paginate((int)$pre,['p.id']);
    }

    public function getProductForName($str,$pre,array $filter,$sort = 'ASC'){

        if (isset($filter['supplier'])){
            foreach ($filter['supplier'] as $k => $item){
                $filter['supplier'][$k] = (int)$item;
            }
        }

        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
            ->where(DB::raw('p.name'),'LIKE',"{$str}%")
            ->where([
                [DB::raw('p.price'),'>=',$filter['price']['min']],
                [DB::raw('p.price'),'<=',$filter['price']['max']]
            ])
            ->join(DB::raw('suppliers AS sp'),DB::raw('sp.id'),DB::raw('p.brand'))
            ->where('p.count','>',0)
            ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
            ->select(DB::raw('sp.id AS supplierId, sp.matchcode, p.id, p.name, p.price, p.articles,p.count,
                    (SELECT a_img.PictureName 
                    FROM article_images AS a_img 
                    WHERE a_img.DataSupplierArticleNumber=p.articles AND a_img.SupplierId=p.brand LIMIT 1) AS file'))
            ->orderBy(DB::raw('p.price'),$sort)
            ->groupBy('p.articles')
            ->havingRaw('MIN(p.price)')
            ->simplePaginate((int)$pre,['p.id']);
    }

    public function getProductByArticle($article, $supplier_id){
        return DB::connection($this->connection)
            ->table('articles')
            ->where('DataSupplierArticleNumber',$article)
            ->where('supplierId',(int)$supplier_id)
            ->first();
    }

    public function getSupplieInfo($supplier_id){
        return DB::connection($this->connection)
            ->table('supplier_details')
            ->where('supplierid',(int)$supplier_id)
            ->first();
    }

    public function getCross($manufacturerId = null,$OENbr = null,$page = null){
        return Cache::remember('cross_' . $manufacturerId . $OENbr . '_page_' . $page, 5, function() use ($manufacturerId, $OENbr) {
            return DB::connection($this->connection)
                ->table('article_cross')
                ->leftJoin('suppliers','suppliers.id','=','article_cross.SupplierId')
                ->where('article_cross.manufacturerId',isset($manufacturerId)?'=':'<>',isset($manufacturerId)?$manufacturerId:null)
                ->where('article_cross.OENbr','LIKE',isset($OENbr)?"%{$OENbr}%":"%%")
                ->select('article_cross.*','suppliers.matchcode')
                ->paginate(20);
        });
    }

    public function getAllCategoryTree($parent = null,$level = null,$modif = null){
        switch ($level){
            case 1:
                $where = "WHERE prd.assemblygroupdescription='{$parent}'";
                $select = " prd.assemblygroupdescription,
                            prd.description as name,prd.id,
                            prd.normalizeddescription,
                            prd.usagedescription,act.image,act.name AS custom_name,act.id AS custom_id,act.parent_category";
                $join_where = ' prd.id = act.tecdoc_id';
                break;
            case 'modif':
                $prd_id = [$parent->tecdoc_id];
                foreach ($parent->subCategory as $subCategory){
                    $prd_id[] = $subCategory->tecdoc_id;
                }

                return DB::connection($this->connection)
                    ->select("SELECT COUNT(DISTINCT p.articles) AS count_product FROM td1q2018.article_links AS al 
                            INNER JOIN lux.products AS p on p.articles = al.DataSupplierArticleNumber  AND p.brand = al.SupplierId
                            WHERE al.productid IN (".implode(',',$prd_id).") AND al.linkageid={$modif} AND p.count > 0");
                break;
            default:
                $where = '';
                $select = ' prd.assemblygroupdescription as name,act.image,act.name AS custom_name,act.id AS custom_id,act.parent_category';
                $join_where = ' prd.assemblygroupdescription = act.tecdoc_name';
        }


        return DB::connection($this->connection)
            ->select("SELECT DISTINCT {$select} FROM prd 
                    LEFT JOIN lux.all_category_trees AS act ON {$join_where}
                    {$where}");
    }

    public function getPdrForId($id){
        return DB::connection($this->connection)->table('prd')->find((int)$id);
    }

    private function getSortAttr($save_attr,$query_attr){
        $attr_filter = [];
        if (!empty($save_attr) && !empty($query_attr)){
            foreach ($query_attr as $k => $val){
                foreach ($save_attr as $item){
                    if ($item->hurl . '_' . $item->filter_id === $k){
                        $buff = explode(',',$val);
                        foreach ($buff as $data){
                            $attr_filter[] = [
                                ['attr.id','=',$item->filter_id],
                                ['attr.displayvalue','=',$data]
                            ];
                        }
                    }
                }
            }
        }

        return $attr_filter;
    }
}
