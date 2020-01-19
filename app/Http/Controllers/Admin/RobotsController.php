<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use MadWeb\Robots\Robots;
use App\Http\Controllers\Controller;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt
     * @param Robots $robots
     * @return ResponseFactory|Response
     */
    public function __invoke(Robots $robots)
    {
        $robots->addUserAgent('*');

        if ($robots->shouldIndex()) {
            // If on the live server, serve a nice, welcoming robots.txt.
            $robots->addDisallow('/admin/*');
            $robots->addDisallow('/profile/*');
            $robots->addDisallow('/liqpay/*');
            $robots->addDisallow('/product_image/*');
            $robots->addHost(Config::get('app.url'));
            $robots->addSitemap('sitemap.xml');
        } else {
            // If you're on any other server, tell everyone to go away.
            $robots->addDisallow('/');
        }

        return response($robots->generate(), 200, ['Content-Type' => 'text/plain']);
    }
}
