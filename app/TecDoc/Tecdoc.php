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
        return cache()->remember('all_suppliers', 60*24, function () {
            return DB::connection($this->connection)->select("
            SELECT id,matchcode
            FROM suppliers
            ORDER BY matchcode"
            );
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
     * @return mixed
     */
    public function getSections($modification_id, $parent = 0,$limit = null)
    {
        $limit_select = isset($limit)?" LIMIT {$limit}":'';
        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)->select("
						SELECT id, description,parentid
						FROM passanger_car_trees WHERE passangercarid=" . (int)$modification_id . " AND parentId=" . (int)$parent . "
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
     * @param string $sort
     * @return mixed
     */
    public function getSectionParts($modification_id, $section_id,$pre = 15,array $filter,$sort = 'ASC')
    {
        switch ($this->type) {
            case 'passenger':
                return DB::connection($this->connection)
                    ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.passanger_car_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                    ->where(DB::raw('al.linkageid'),DB::raw('pds.passangercarid'))
                    ->where(DB::raw("al.linkageid"),(int)$modification_id)
                    ->where(DB::raw("pds.nodeid"),(int)$section_id)
                    ->where(DB::raw('al.linkagetypeid'),2)
                    ->where([
                        [DB::raw('p.price'),'>=',$filter['price']['min']],
                        [DB::raw('p.price'),'<=',$filter['price']['max']]
                    ])
                    ->whereRaw(isset($filter['supplier'])? " s.id IN (".implode(',',$filter['supplier']).")":'s.id > 0')
                    ->select(DB::raw('al.datasupplierarticlenumber DataSupplierArticleNumber, s.description matchcode,al.supplierid supplierId, prd.description NormalizedDescription,p.id,p.name,p.price,p.count'))
                    ->orderBy(DB::raw('p.price'),$sort)
                    ->distinct()
                    ->paginate($pre);
                break;
            case 'commercial':
                return DB::connection($this->connection)
                    ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links AS al'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_pds AS pds'),DB::raw('al.supplierid'),DB::raw('pds.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS s'),DB::raw('s.id'),DB::raw('al.supplierid'))
                    ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.commercial_vehicle_prd AS prd'),DB::raw('prd.id'),DB::raw('al.productid'))
                    ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
                    ->where(DB::raw('al.productid'),DB::raw('pds.productid'))
                    ->where(DB::raw('al.linkageid'),DB::raw('pds.commertialvehicleid'))
                    ->where(DB::raw('al.linkageid'),(int)$modification_id)
                    ->where(DB::raw('pds.nodeid'),(int)$section_id)
                    ->where(DB::raw('al.linkagetypeid'),16)
                    ->where([
                        [DB::raw('p.price'),'>=',$filter['price']['min']],
                        [DB::raw('p.price'),'<=',$filter['price']['max']]
                    ])
                    ->whereRaw(isset($filter['supplier'])? " s.id IN (".implode(',',$filter['supplier']).")":'s.id > 0')
                    ->select(DB::raw('al.datasupplierarticlenumber DataSupplierArticleNumber, s.description matchcode,al.supplierid supplierId, prd.description NormalizedDescription,p.id,p.name,p.price,p.count'))
                    ->orderBy(DB::raw('p.price'),$sort)
                    ->distinct()
                    ->paginate($pre);
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

    public function getManufacturerForOed($oem_id,$matchcode,$alies_manfactias){

        $lifter = [
            ['oe.OENbr',$oem_id]
        ];

        if (isset($matchcode)){
            $str = isset($alies_manfactias[$matchcode])?$alies_manfactias[$matchcode]:$matchcode;
            $lifter[] = ['m.matchcode',$str];
        }

        return DB::connection($this->connection)
            ->table('manufacturers as m')
            ->join('article_oe as oe','oe.manufacturerId','=','m.id')
            ->where($lifter)
            ->select(DB::raw('m.id, m.matchcode, m.description'))
            ->distinct()
            ->first();
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
        $rows = DB::connection($this->connection)->select("
            SELECT linkageTypeId, linkageId FROM article_li WHERE DataSupplierArticleNumber='" . $number . "' AND supplierId=" . (int)$brand_id . "
        ");
        foreach ($rows as $row) {
            switch ($row) {
                case 'PassengerCar':
                    $result[$row['linkageTypeId']][] = DB::connection($this->connection)->select("SELECT DISTINCT p.id, mm.description make, m.description model, p.constructioninterval, p.description FROM passanger_cars p 
                        JOIN models m ON m.id=p.modelid
                        JOIN manufacturers mm ON mm.id=m.manufacturerid
                        WHERE p.id=" . $row['linkageId']);
                    break;
                case 'CommercialVehicle':
                    $result[$row['linkageTypeId']][] = DB::connection($this->connection)->select("SELECT DISTINCT p.id, mm.description make, m.description model, p.constructioninterval, p.description FROM commercial_vehicles p 
                        JOIN models m ON m.id=p.modelid
                        JOIN manufacturers mm ON mm.id=m.manufacturerid
                        WHERE p.id=" . $row['linkageId']);
                    break;
                case 'Motorbike':
                    $result[$row['linkageTypeId']][] = DB::connection($this->connection)->select("SELECT DISTINCT p.id, mm.description make, m.description model, p.constructioninterval, p.description FROM motorbikes p 
                        JOIN models m ON m.id=p.modelid
                        JOIN manufacturers mm ON mm.id=m.manufacturerid
                        WHERE p.id=" . $row['linkageId']);
                    break;
                case 'Engine':
                    $result[$row['linkageTypeId']][] = DB::connection($this->connection)->select("SELECT DISTINCT p.id, m.description make, '' model, p.constructioninterval, p.description FROM `engines` p 
                        JOIN manufacturers m ON m.id=p.manufacturerid
                        WHERE p.id=" . $row['linkageId']);
                    break;
                case 'Axle':
                    $result[$row['linkageTypeId']][] = DB::connection($this->connection)->select("SELECT DISTINCT p.id, mm.description make, m.description model, p.constructioninterval, p.description FROM axles p 
                        JOIN models m ON m.id=p.modelid
                        JOIN manufacturers mm ON mm.id=m.manufacturerid
                        WHERE p.id=" . $row['linkageId']);
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
        return DB::connection($this->connection)->select("
            SELECT DISTINCT s.description, c.PartsDataSupplierArticleNumber FROM article_oe a
            JOIN manufacturers m ON m.id=a.manufacturerId 
            JOIN article_cross c ON c.OENbr=a.OENbr
            JOIN suppliers s ON s.id=c.SupplierId
            WHERE a.datasupplierarticlenumber='" . $number . "' AND a.manufacturerId='" . $brand_id . "'
        ");
    }

    public function getAccessories($number)
    {
        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_acc as acc'))
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('acc.AccDataSupplierArticleNumber'))
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_images as img'),function ($join){
                $join->on(DB::raw('img.DataSupplierArticleNumber'),'=',DB::raw('acc.AccDataSupplierArticleNumber'));
                $join->on(DB::raw('img.SupplierId'),'=',DB::raw('acc.AccSupplierId'));
            })
            ->where(DB::raw('acc.DataSupplierArticleNumber'),$number)
            ->select(DB::raw('acc.AccSupplierId AS supplierId, acc.AccDataSupplierArticleNumber DataSupplierArticleNumber,img.PictureName, p.brand matchcode, p.id, p.name, p.price,p.old_price,p.count'))
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

    public function getCategoryProduct($id,$pre,array $filter,$sort = 'ASC'){
        if (isset($filter['supplier'])){
            foreach ($filter['supplier'] as $k => $item){
                $filter['supplier'][$k] = (int)$item;
            }
        }
        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql_tecdoc.database').'.article_links as al'))
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers as s'),DB::raw('s.id'),DB::raw('al.supplierid'))
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('al.DataSupplierArticleNumber'))
            ->where(DB::raw('al.linkageid'),(int)$id)
            ->where(DB::raw('al.linkagetypeid'),($this->type) === 'passenger'?2:16)
            ->where([
                [DB::raw('p.price'),'>=',$filter['price']['min']],
                [DB::raw('p.price'),'<=',$filter['price']['max']]
            ])
            ->whereRaw(isset($filter['supplier'])? " s.id IN (".implode(',',$filter['supplier']).")":'s.id > 0')
            ->select(DB::raw('al.SupplierId AS supplierId, al.DataSupplierArticleNumber, s.matchcode, p.id, p.name, p.price,p.count'))
            ->orderBy(DB::raw('p.price'),$sort)
            ->distinct()
            ->paginate((int)$pre);
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
            ->join(DB::raw(config('database.connections.mysql.database').'.products AS p'),DB::raw('p.articles'),DB::raw('ac.PartsDataSupplierArticleNumber'))
            ->where(DB::raw('ac.OENbr'),$OENbr)
            ->where(DB::raw('ac.manufacturerId'),(int)$manufacturer_id)
            ->where([
                [DB::raw('p.price'),'>=',$filter['price']['min']],
                [DB::raw('p.price'),'<=',$filter['price']['max']]
            ])
            ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
            ->select(DB::raw('sp.id AS supplierId, ac.PartsDataSupplierArticleNumber as DataSupplierArticleNumber, sp.matchcode, p.id, p.name, p.price,p.count'))
            ->orderBy(DB::raw('p.price'),$sort)
            ->distinct()
            ->paginate((int)$pre);
    }

    public function getProductForName($str,$pre,array $filter,$sort = 'ASC'){

        if (isset($filter['supplier'])){
            foreach ($filter['supplier'] as $k => $item){
                $filter['supplier'][$k] = (int)$item;
            }
        }
        return DB::connection($this->connection)
            ->table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
            ->where(DB::raw('p.name'),'LIKE',"%{$str}%")
            ->where([
                [DB::raw('p.price'),'>=',$filter['price']['min']],
                [DB::raw('p.price'),'<=',$filter['price']['max']]
            ])
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),DB::raw('sp.matchcode'),DB::raw('p.brand'))
            ->whereRaw(isset($filter['supplier'])? " sp.id IN (".implode(',',$filter['supplier']).")":'sp.id > 0')
            ->select(DB::raw('sp.id AS supplierId, sp.matchcode, p.id, p.name, p.price, p.articles,p.count'))
            ->orderBy(DB::raw('p.price'),$sort)
            ->groupBy(DB::raw('p.articles,p.count,p.name,p.id,sp.matchcode,sp.id'))
            ->havingRaw('MIN(p.price)')
            ->paginate((int)$pre);
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

    public function getAllCategoryTree($parent = null,$level = null){

        switch ($level){
            case 1:
                $where = "WHERE assemblygroupdescription='{$parent}'";
                $select = " normalizeddescription as name,id";
                break;
            default:
                $where = '';
                $select = ' assemblygroupdescription as name';
        }

        return DB::connection($this->connection)
            ->select("SELECT DISTINCT {$select} FROM prd {$where}");
    }
}
