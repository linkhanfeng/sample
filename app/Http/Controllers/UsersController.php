<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UsersController extends Controller
{
    // 创建用户
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required'
        ]);
        return;
    }
}