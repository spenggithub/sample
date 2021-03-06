<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
class UsersController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth',[
           'except'=>['create','show','store']
        ]);
        $this->middleware('guest',[
           'only'=>['create']
        ]);
    }

//    get注册页面
    public function create()
    {
        return view('users.create');
    }
    //用户页面
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }
//    注册用户
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    //修改用户信息页面
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }
    //修改用户信息
    public function update(User $user,Request $request)
    {

        $this->validate($request,[
           'name'=>'required|max:50',
            'password' => 'required|confirmed|min:6'
        ]);
        $this->authorize('update',$user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show',$user);
    }
    //列出所有操作
    public function index()
    {
        $users = User::all();
        return view('users.index',compact('users'));
    }

}
