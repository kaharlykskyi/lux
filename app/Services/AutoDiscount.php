<?php


namespace App\Services;


use App\{Cart, Discount, User};
use Illuminate\Support\Facades\Log;
use LisDev\Delivery\NovaPoshtaApi2;

class AutoDiscount
{

    public function __construct()
    {
        $this->startSetDiscount();
    }

    /**
     * Set discount for user automatically
     */
    protected function startSetDiscount(){
        $users = User::with(['discount','cart' => function($query){
            $query->whereIn('oder_status', [4,6]);
        }])->where('permission','<>','block')->get();
        $discount = Discount::where('count_buy','<>',null)->get();

        foreach ($users as $user){
            $count_complete_order = 0;
            foreach ($user->cart as $item){
                if($item->oder_status === 6){
                    $count_complete_order++;
                }
                if ($item->oder_status === 4 && isset($item->invoice_np)){
                    try{

                        $np = new NovaPoshtaApi2(config('app.novaposhta_key'),'ru');

                        $data_track = $np->documentsTracking($item->invoice_np);
                        $data_track = $data_track['data'][0];
                        if ((int)$data_track['StatusCode'] === 9){
                            Cart::where('id',$item->id)->update([
                                'oder_status' => 6
                            ]);

                            $count_complete_order++;
                        }

                    }catch (\Exception $e){
                        if (config('app.debug')){
                            dump($e);
                        } else {
                            Log::error($e);
                        }
                    }
                }
            }
            foreach ($discount as $value){
                if ($value->count_buy <= $count_complete_order && $value->id !== $user->discount_id){
                    User::where('id',$user->id)->update(['discount_id' => $value->id]);
                }
            }
        }
    }
}