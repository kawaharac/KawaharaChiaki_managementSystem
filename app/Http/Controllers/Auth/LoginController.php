<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //擬似変数this(クラス内のメソッドにアクセスする)からmiddlewareの引数guestにアクセス>
        //except()で指定したキー「以外」のデータを取得する
    }

    public function loginView()
    {
        return view('auth.login.login');

        //auth/login directoryの中のlogin.bladeが該当ファイル
    }

    public function loginPost(Request $request)
    {
        $userdata = $request -> only('mail_address', 'password');
        if (Auth::attempt($userdata)) {
            //ログイン認証をする為のデータがあればtop画面へ行く
            return redirect('/top');
        }else{
            return redirect('/login')->with('flash_message', 'name or password is incorrect');
            //そうでなければログイン画面にメッセージ付きで戻す
        }
    }

}
