<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Auth;

// Authenticatable 授权相关功能的引用
class User extends Authenticatable
{
    // 消息通知相关功能引用
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * 允许修改的属性 过滤防止 批量赋值
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *敏感信息在用户实例通过数组或 JSON 显示时进行隐藏
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 创建全球通用头像
     * @param  string $size 头像尺寸
     * @return string       头像链接
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * 指明一个用户拥有多条微博
     * ::class 可用于类名的解析, 使用 ClassName::class 你可以获取一个字符串，包含了类 ClassName 的完全限定名称。这对使用了 命名空间 的类尤其有用
     * Status::class 将被解析为 App\Models\Status 模型
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    // 将当前用户发布过的所有微博从数据库中取出，并根据创建时间来倒序排序。在后面我们为用户增加关注人的功能之后，将使用该方法来获取当前用户关注的人发布过的所有微博动态
    public function feed()
    {
        // return $this->statuses()->orderBy('created_at', 'desc'); // 获取用户自己的所有动态
        // 1. 获取关注的人 id 列表
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        // 2. 加入 自己的 id
        array_push($user_ids, Auth::user()->id);
        // 3. 使用 Laravel 提供的 查询构造器 whereIn 方法取出所有用户的微博动态并进行倒序排序；
        // 4. 我们使用了 Eloquent 关联的 预加载 with 方法，预加载避免了 N+1 查找的问题，大大提高了查询效率。N+1 问题 的例子可以阅读此文档 https://laravel-china.org/docs/laravel/5.5/eloquent-relationships/1333#%E9%A2%84%E5%8A%A0%E8%BD%BD
        return Status::whereIn('user_id', $user_ids)
                              ->with('user')
                              ->orderBy('created_at', 'desc');
    }

    // 粉丝 (通过 followers 来获取粉丝列表) // $user->followers();
    public function followers()
    {
        // return $this->belongsToMany(User::class)
        // 自定义生成的名称，把关联表名改为 followers
        // 自定义数据表里的字段名称 user_id 是定义在关联中的模型外键名，而第四个参数 follower_id 则是要合并的模型外键名
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 我的关注 (通过 followings 来获取用户关注人列表)
    // $user->followings 与 $user->followings() 调用时返回的数据是不一样的， $user->followings 返回的是 Eloquent：集合 。
    // 而 $user->followings() 返回的是 数据库请求构建器 ，followings() 的情况下，你需要使用：
    // 可以简单理解为 followings 返回的是数据集合，而 followings() 返回的是数据库查询语句
    // $user->followings == $user->followings()->get() // 等于 true
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注功能
    public function follow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    // 取消关注功能
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 判断是否关注过此用户
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}