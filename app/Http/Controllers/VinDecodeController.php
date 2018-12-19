<?php

namespace App\Http\Controllers;

use App\TecDoc\VinDecoder;
use Illuminate\Http\Request;

class VinDecodeController extends Controller
{
    public function index(Request $request){
        $vin_decoder = new VinDecoder($request->post('vin'));

        /*dump($vin_decoder->getRegion());
        dump($vin_decoder->getCountry());
        dump($vin_decoder->getManufacturer());*/
    }
}
