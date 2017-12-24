<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        // 不需要验证可以访问的方法
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store'] // 排除不需要验证的方法
        ]);
        // 只让未登录用户访问注册页面：
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    // 注册表单
    public function create()
    {
        return view('users.create');
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

        session()->flash('success', trans('msg.user_register_success'));
        return redirect()->route('users.show', [$user->id]); // === return redirect()->route('users.show', [$user->id]);
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
     * 显示编辑表单
     * @param  User   $user [隐形路由]
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', trans('msg.user_update_success'));
        return redirect()->route('users.show', $user->id);
    }
}