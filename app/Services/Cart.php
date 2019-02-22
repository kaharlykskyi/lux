<?php

namespace App\Services;


use App\User;

class Cart
{
    public function getSumOrder($products){
        $sum = 0.00;
        foreach ($products as $product){
            $sum += (float)$product->price * (int)$product['pivot']['count'];
        }
        return $sum;
    }

    public function getCostProduct($products,$id_product){
        $product_cost = 0.00;
        foreach ($products as $product){
            if ($product->id === (int)$id_product){
                $product_cost = (float)$product->price * (int)$product['pivot']['count'];
            }
        }
        return $product_cost;
    }

    public function getDiscountSum($user_id,$sum){
        $total = $sum;
        $user = User::with('discount')->find((int)$user_id);
        if (isset($user->discount)){
            $total -= $total * $user->discount->percent / 100;
        }
        return round($total,2);
    }
}