<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user_ids = ['1', '2', '3'];
        $faker = app(Faker\Generator::class); // 通过 app() 方法来获取一个 Faker 容器 的实例

        $statuses = factory(Status::class)->times(100)->make()->each(function($status) use ($faker, $user_ids) {
            // 并借助 randomElement 方法来取出用户 id 数组中的任意一个元素并赋值给微博的 user_id，使得每个用户都拥有不同数量的微博
            $status->user_id = $faker->randomElement($user_ids);
        });

        Status::insert($statuses->toArray());
    }
}
