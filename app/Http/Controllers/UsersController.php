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
            // 除了 except 数组规定的方法外, 其他的方法都需要登录通过 auth 验证
            'except' => ['show', 'create', 'store', 'index'] // 排除不需要验证的方法
        ]);
        // 只让未登录用户访问注册页面：
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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

    /**
     * 更改用户个人信息
     */
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

    /**
     * 删除用户
     * 在 destroy 动作中，我们首先会根据路由发送过来的用户 id 进行数据查找，
     * 查找到指定用户之后再调用 Eloquent 模型提供的 delete 方法对用户资源进行删除，
     * 成功删除后在页面顶部进行消息提示。最后将用户重定向到上一次进行删除操作的页面，即用户列表页。
     */
    public function destroy(User $user)
    {
        /**
         * 删除授权策略 App/Policies/UserPolicy::destroy 我们已经创建了，
         * 这里我们在用户控制器中使用 authorize 方法来对删除用户的操作进行授权验证即可。
         */
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success', trans('msg.destroy_user_success')); // 成功删除用户
        return back();
    }
}