<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use App\Models\User;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    // 验证用户登录
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        // Auth::user() 就是当前的用户的 eloquent orm 模型
        // $user = User::find(1);
        // dd(Auth::user() == $user); // true
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * 『隐性路由模型绑定』
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        // 做删除授权的检测，不通过会抛出 403 异常。
        $this->authorize('destroy', $status);
        // 调用 Eloquent 模型的 delete 方法对该微博进行删除
        $status->delete();
        session()->flash('success', '微博已被成功删除!');
        return redirect()->back();
    }
}
