<?php

namespace App\Http\Controllers;

use App\Mail\FeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    public function index(Request $request){
        $data = $request->post();

        $validate = Validator::make($data,[
            'phone' => 'required|string|max:15|unique:feedback',
            'name' => 'required|string|max:255'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'response' => 'вы уже отправляли запрос, ожидайте пока менеджер свяхеться с вами'
            ]);
        }

        DB::table('feedback')->insert([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'message' => $data['message'],
            'email' => $data['email'],
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        Mail::to(config('mail.from.address'))->send(new FeedBack($data));

        return response()->json([
            'response' => 'Запрос сделан'
        ]);
    }
}
