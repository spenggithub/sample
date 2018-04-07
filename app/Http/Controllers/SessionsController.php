<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    //只有未登录用户才能访问登录页面
    public function __construct()
    {
        $this->middleware('guest',[
           'only'=>'create'
        ]);
    }

    //用户登录页面
    public function create()
    {
        return view('sessions.create');
    }
    //用户登录
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
           'email'=>'required|email|max:255',
           'password'=>'required'
        ]);
        if (Auth::attempt($credentials,$request->has('remember'))){
            session()->flash('success','欢迎回来');
            return redirect()->intended(route('users.show',[Auth::user()]));
        } else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
        return;
    }
    //用户退出
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect()->route('login');
    }

}
