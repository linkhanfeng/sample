<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    // laravel 的 授权策略
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user)
    {
        // 当前用户是自己
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser, User $user)
    {
        // 只有当前用户拥有管理员权限且删除的用户不是自己时才显示链接。
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}