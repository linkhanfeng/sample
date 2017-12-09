<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Authenticatable 授权相关功能的引用
class User extends Authenticatable
{
    // 消息通知相关功能引用
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * 允许修改的属性
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
}
