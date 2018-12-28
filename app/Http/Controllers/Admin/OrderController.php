<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = DB::select("SELECT c.id, c.updated_at,c.oder_status,u.name,
                                      (SELECT SUM(p.price * cp.count) FROM `products` AS p 
                                              JOIN `cart_products` AS cp WHERE p.id=cp.product_id AND cp.cart_id=c.id) AS total_price
                                      FROM `carts` AS c
                                      JOIN users as u ON u.id=c.user_id ORDER BY c.updated_at DESC");
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($orders);
        $perPage = 2;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->fullUrl());

        $order_code = DB::table('oder_status_codes')->get();

        return view('admin.orders.index',compact('paginatedItems','order_code'));
    }
}
