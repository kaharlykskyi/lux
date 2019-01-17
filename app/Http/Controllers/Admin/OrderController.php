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
        $orders = DB::select("SELECT c.id, c.updated_at,c.oder_status,u.name,c.invoice_np,
                                      (SELECT SUM(p.price * cp.count) FROM `products` AS p 
                                              JOIN `cart_products` AS cp WHERE p.id=cp.product_id AND cp.cart_id=c.id) AS total_price
                                      FROM `carts` AS c
                                      JOIN users as u ON u.id=c.user_id ORDER BY c.updated_at DESC");
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($orders);
        $perPage = 30;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->fullUrl());

        $order_code = DB::table('oder_status_codes')->get();

        return view('admin.orders.index',compact('paginatedItems','order_code'));
    }

    public function getOrderData(Request $request){
        $product_data = DB::select("SELECT p.id,p.price,p.name,p.articles,cp.count AS count_in_cart FROM `products` AS p 
                                          JOIN `cart_products` AS cp ON cp.product_id=p.id 
                                          WHERE cp.product_id=p.id AND cp.cart_id={$request->idOrder}");
        return response()->json([
            'response' => $product_data
        ]);
    }

    public function getInfoProductStock(Request $request){
        $product_stock = DB::select("SELECT s.name,s.company,sp.count FROM `stocks` AS s 
                                            JOIN `stock_products` AS sp ON sp.stock_id=s.id
                                            WHERE sp.product_id={$request->productID}");

        return response()->json([
            'response' => $product_stock
        ]);
    }

    public function changeStatusOrder(Request $request){
        if (isset($request->invoice)){
            DB::table('carts')->where('id',$request->orderID)
                ->update([
                    'invoice_np' => $request->invoice
                ]);

            return response()->json([
                'response' => 'Номер накладной сохранён'
            ]);
        }

        DB::table('carts')->where('id',$request->orderID)
            ->update([
                'oder_status' => (int)$request->statusID
            ]);

        return response()->json([
            'response' => 'Статус заказа изменен'
        ]);
    }
}
