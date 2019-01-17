<?php

namespace App\Http\Controllers;

use App\Cart;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LisDev\Delivery\NovaPoshtaApi2;

class TrackOrderController extends Controller
{
    public function index(Request $request){
        $order = Cart::find($request->id);
        $data_track = null;

        if(isset($order->invoice_np) && $order->oder_status === 4){
            try{

                $np = new NovaPoshtaApi2(config('app.novaposhta_key'),'ru');

                $data_track = $np->documentsTracking($order->invoice_np);
                $data_track = $data_track['data'][0];

            }catch (\Exception $e){
                if (config('app.debug')){
                    dump($e);
                } else {
                    Log::error($e);
                }
            }
        }

        return view('track_order.index',compact('order','data_track'));
    }
}
