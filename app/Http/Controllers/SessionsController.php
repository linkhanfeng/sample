<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    /**
     * 登录页
     */
    public function create()
    {
        return view('sessions.create');
    }
    /**
     * 创建用户
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255', // 此处仅需要保证用户输入不为空,且格式正确即可; 注册时需要保证唯一性
            'password' => 'required'
        ]);
        /**
         * attempt(['email' => $email, 'password' => $pass])
         * 方法 验证 用户逻辑
         * if( $email 不在数据库 users 表中 ){
         *   return false;
         * }
         *
         * if( hash($pass) === 数据库中的加密密码  ){
         *    创建一个 session 文件 (保存session_id 对应的信息, 默认保存在 storage/framework/sessions )
         *    并 在 http 返回头中 设置一个名为 laravel_session 的 HTTP Cookie (将加密后的 session_id 保存在浏览器)
         *    return true;
         * } else {
         *    return false;
         * }
         *
         * @var [type]
         */
        if(Auth::attempt($credentials)) {
            // 登录成功后的相关操作
            session()->flash('success', trans('msg.user_login_success',['username' => Auth::user()->name]));
            return redirect()->route('users.show', [Auth::user()]); // 等同于 return redirect()->route('users.show', [Auth::user()->id]);
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', trans('msg.user_not_exists'));
            return redirect()->route('login');
        }
        return;
    }
}