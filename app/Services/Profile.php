<?php


namespace App\Services;


use App\{AppTrait\GEO, DeliveryInfo, UserCar, Cart, UserPhone};
use Illuminate\Support\Facades\{DB, Log, Validator};
use LisDev\Delivery\NovaPoshtaApi2;

class Profile
{
    use GEO;

    public function getOrders($user_id){
        $orders = DB::select("SELECT c.id, c.updated_at,osc.name as status,osc.id as oder_status, c.invoice_np,
                                      (SELECT SUM(p.price * cp.count) FROM `products` AS p 
                                              JOIN `cart_products` AS cp WHERE p.id=cp.product_id AND cp.cart_id=c.id) AS total_price
                                      FROM `carts` AS c
                                      JOIN `oder_status_codes` as osc ON osc.id=c.oder_status
                                      WHERE c.user_id={$user_id} AND c.oder_status<>1 ORDER BY c.updated_at DESC");

        foreach ($orders as $k => $order){
            if(isset($order->invoice_np) && ($order->oder_status === 4 || $order->oder_status === 6)){
                try{
                    $data_track = $this->getNPData($order->invoice_np);
                    if ((int)$data_track['StatusCode'] === 9){
                        Cart::where('id',$order->id)->update([
                            'oder_status' => 6
                        ]);
                    }
                    $orders[$k]->track_data = $data_track;
                }catch (\Exception $e){
                    if (config('app.debug')){
                        dump($e);
                    } else {
                        Log::error($e);
                    }
                }
            }
        }

        return $orders;
    }

    protected function getNPData($invoice_np){
        $np = new NovaPoshtaApi2(config('app.novaposhta_key'),'ru');

        $data_track = $np->documentsTracking($invoice_np);
        $data_track = $data_track['data'][0];

        return $data_track;
    }

    public function setCar(array $data,$user_id){

        $validate = Validator::make($data,[
            'vin_code' => 'required',
            'type_auto' => 'required',
            'year_auto' => 'required|min:4',
            'brand_auto' => 'required',
            'model_auto' => 'required',
            'modification_auto' => 'required'
        ]);

        if ($validate->fails()) {
            return ['errors' => $validate->errors()];
        }

        $data['user_id'] = $user_id;

        $userCar = new UserCar();
        $userCar->fill($data);

        if ($userCar->save()){
            return ['response' => $userCar];
        } else {
            return ['errors' => 'Ошибка, попробуйте позже!'];
        }
    }

    public function serDeliveryInfo(array $data,$user_id){
        if(DB::table('delivery_info')->where('user_id',$user_id)->exists()){
            $delivery_info = DB::table('delivery_info')->where('user_id', $user_id)->first();

            if($delivery_info->delivery_country !== $data['delivery_country'] && isset($data['delivery_country'])){
                $country = $this->parseCountry($data['delivery_country']);
                $data['delivery_country'] = "{$country->name} ({$country->alpha2})";
                if ($delivery_info->delivery_city !== $data['delivery_city'] && isset($data['delivery_city'])){
                    $city = $this->parseCity($data['delivery_city'],$country->id);
                    $data['delivery_city'] = $city->name;
                }
            } else {
                if($delivery_info->delivery_city !== $data['delivery_city'] && isset($data['delivery_city'])){
                    $del_country = explode(' ', $delivery_info->delivery_country,2);
                    $country = DB::table('country')->where('name', $del_country[0])->first();
                    $city = $this->parseCity($data['delivery_city'],$country->id);
                    $data['delivery_city'] = $city->name;
                }
            }

            DeliveryInfo::where('user_id',$user_id)->update($data);
        } else {
            if (isset($data['delivery_country'])){
                $country = $this->parseCountry($data['delivery_country']);
                $data['delivery_country'] = "{$country->name} ({$country->alpha2})";
                if (isset($data['delivery_city'])){
                    $city = $this->parseCity($data['delivery_city'],$country->id);
                    $data['delivery_city'] = $city->name;
                }
            }
            $data['user_id'] = $user_id;

            $delivery_info = new DeliveryInfo();
            $delivery_info->fill($data);
            if(!$delivery_info->save($data)){
                return ['response' => 'Ошибка, попробуйте ещё'];
            }
        }

        return ['response' => 'Данные сохранены'];
    }

    public function setUserInfo($user,$data){
        $validate = Validator::make($data,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|' . (($user->email !== $data['email']) ? 'unique:users':''),
            'sername' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'country' => 'required',
            'city' => 'required',
            'role' => 'required'
        ]);

        if ($validate->fails()) {
            return ['errors' => $validate->errors()];
        }

        if ($user->country !== $data['country']){
            $country = $this->parseCountry($data['country']);
            $data['country'] = $country->name;

            if ($user->city !== $data['city']){
                $city = $this->parseCity($data['city'],$country->id);
                $data['city'] = $city->name;
            }
        } else {
            if ($user->city !== $data['city']){
                $country = DB::table('country')->where('name','=',$user->country)->first();
                $city = $this->parseCity($data['city'],$country->id);
                $data['city'] = $city->name;
            }
        }

        DB::table('users')->where('id',$user->id)->update($data);

        return ['response' => 'Данные обновлены'];
    }

    public function getTrackOrder($id_order,$user_id){
        $order = Cart::find((int)$id_order);

        if (!isset($order)){
            return ['order' => $order = (object)['oder_status' => -1]];
        }

        if ($order->user_id !== $user_id){
            return ['no_success' => 'Заказ с таким идентификатором не пренадлежит даной учетной записи'];
        }

        if(isset($order->invoice_np) && $order->oder_status === 4){
            try{
                $data_track = $this->getNPData($order->invoice_np);
                $order->track_data = $data_track;
            }catch (\Exception $e){
                if (config('app.debug')){
                    dump($e);
                } else {
                    Log::error($e);
                }
            }
        }

        return ['order' => $order];
    }

    public function setDopUserPhone($phone,$user_id){
        $data = ['user_id' => $user_id,'phone' => $phone];
        $validate = Validator::make($data,[
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'user_id' => 'required',
        ]);
        if ($validate->fails()) {
            return ['errors' => $validate->errors()];
        }

        if (UserPhone::where('user_id',$user_id)->count() > 5){
            return ['errors' => 'Превышен лимит'];
        }

        $new_phone = new UserPhone();
        $new_phone->fill($data);
        if ($new_phone->save()){
            return ['response' => $new_phone];
        }else{
            return ['errors' => 'данные не сохранены'];
        }
    }

    public function delDopUserPhone($phone_id,$user_id){
        DB::table('user_phones')
            ->where('id',(int)$phone_id)
            ->where('user_id',$user_id)->delete();
        return ['response' => 'Номер удалён'];
    }
}