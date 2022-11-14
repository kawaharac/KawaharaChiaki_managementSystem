<?php

namespace App\Http\Controllers\Authenticated\Top;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class TopsController extends Controller
{
    public function show(){
        return view('authenticated.top.top');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
        //Auth（認証）クラスのログアウトメソッドを呼び出す。（スコープ演算子）
        //返し値でログインページに移動させる。（Route::post()で呼び出すメソッド内に記述。redirect()が実行されると、web.phpのRoute::get()が読み込まれる）
    }
}
