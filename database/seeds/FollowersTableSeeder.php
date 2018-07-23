<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 使用第一个用户对除自己以外的用户进行关注
     * 接着再让所有用户去关注第一个用户
     * @return void
     */
    public function run()
    {
        //
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 获取除了 id 为 1 的所有用户 id 数组
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        // 关注除了 1 号 用户以外所有用户
        $user->follow($follower_ids);

        // 除了 1 号用户以外的所有用户都来关注 1 号用户
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
