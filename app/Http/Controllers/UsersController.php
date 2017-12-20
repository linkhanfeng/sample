<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Auth;

class UsersController extends Controller
{
    // 注册表单
    public function create()
    {
        return view('users.create');
    }

    /**
     * 显示用户个人信息
     * @param  User   $user 声明一个 Eloquent 类型的变量 $user, 并且与 URI 中的变量 {user} 相对应
     * @return [type]       视图
     */
    public function show(User $user)
    {
        // dd(compact('user'));
        return view('users.show', compact('user'));
    }

    /**
     * 存储注册的用户
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed'
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // 注册成功后自动登录 方法
        Auth::login($user);

        session()->flash('success', trans('user_register_success'));
        return redirect()->route('users.show', [$user->id]); // === return redirect()->route('users.show', [$user->id]);
    }
}