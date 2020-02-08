<?php

namespace App\Http\Controllers\Admin;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;

class SiteMapController extends Controller
{
    public function index()
    {
        ini_set('max_execution_time', 3000);

        if (File::exists(public_path('sitemap.xml'))) {
            File::delete(public_path('sitemap.xml'));
        }

        SitemapGenerator::create('http://carmakers.com.ua/sitemap/get-links')
            ->configureCrawler(function (Crawler $crawler) {
                $crawler->ignoreRobots();
            })
            ->setMaximumCrawlCount(100000)
            ->writeToFile('sitemap.xml');

        if (File::exists(public_path('sitemap.xml'))) {
            return Response::download(public_path('sitemap.xml'));
        }
    }

    public function getLinks()
    {
        $tecdoc = new Tecdoc('mysql_tecdoc');
        $brands = DB::table('show_brand')
            ->where('ispassengercar', '=', 'true')
            ->select('brand_id AS id', 'description')->get();

        foreach ($brands as $k => $brand){
            $models = $tecdoc->getModels($brand->id);
            foreach ($models as $model) {
                echo '<a href="'.Config::get('app.url') . 'brands?brand='.$brand->id.'&model='.$model->id.'">'.$model->name.'</a>';
            }
        }
    }
}
