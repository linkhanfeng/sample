<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 只让未登录用户访问登录页面：
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    /**
     * 登录表单
     */
    public function create()
    {
        return view('sessions.create');
    }
    /**
     * 保存登录状态
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255', // 此处仅需要保证用户输入不为空,且格式正确即可; 注册时需要保证唯一性
            'password' => 'required'
        ]);

        // dd($request->all(), $request->has('remember'));
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
         * attempt ($credentials, '(bool) 是否记住我 默认记住两个小时,如过选择记住我功能则记住 5 年')
         * @var [type]
         */
        if(Auth::attempt($credentials,$request->has('remember'))) {
            // 登录成功后的相关操作
            session()->flash('success', trans('msg.user_login_success',['username' => Auth::user()->name]));

            // return redirect()->route('users.show', [Auth::user()]); // 等同于 return redirect()->route('users.show', [Auth::user()->id]);
            // redirect() 实例提供了一个 intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，当上一次请求记录为空时，跳转到默认地址上。
            return redirect()->intended(route('users.show', [Auth::user()]));
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', trans('msg.user_not_exists'));
            // return redirect()->route('login');
            return redirect()->back();
        }
        return;
    }
    /**
     * 登出
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', trans('msg.user_logout_success'));
        return redirect('login');
    }
}