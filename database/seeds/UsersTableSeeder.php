<?php

use Illuminate\Database\Seeder;

/**
 * use User 模型
 */
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * times 和 make 方法是由 FactoryBuilder 类 提供的 API。
         * times 接受一个参数用于指定要创建的模型数量，
         * make 方法调用后将为模型创建一个 集合。
         * @var [type]
         */
        $users = factory(User::class)->times(50)->make();
        /**
         * makeVisible 方法临时显示 User 模型里指定的隐藏属性 $hidden 使 User 模型 可以临时插入 隐藏的属性
         */
        User::insert(
            $users->makeVisible(['password', 'remember_token'])->toArray()
        );

        // 更新第一个用户 方便后面的操作
        $user = User::find(1);
        $user->name = 'hanfeng';
        $user->email = 'linkhanfeng@gmail.com';
        $user->password = bcrypt('1');
        $user->is_admin = true;
        $user->save();
        // User::where('id',1)->update([
        //     'name' => 'hanfeng',
        //     'email' => 'linkhanfeng@gmail.com',
        //     'password' => bcrypt('1'),
        // ]);
    }
}