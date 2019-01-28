<?php

namespace App\Http\Controllers\Admin;

use App\Mail\FeedBackAsk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FeedBackController extends Controller
{
    public function index(){
        $feedback = DB::table('feedback')->orderBy('created_at','DESC')->paginate(20);

        return view('admin.feedback.index',compact('feedback'));
    }

    public function delete(Request $request){
        DB::table('feedback')->delete($request->id);
        return back();
    }

    public function sendFeedBack(Request $request){
        $data = $request->except('_token');
        try{
            Mail::to($data['recipient'])->send(new FeedBackAsk($data));
            return back()->with('status','Сообщение отправлено');
        } catch (\Exception $e){
            return back()->with('status','Произошла ошибка. Повторите через время');
        }
    }
}
