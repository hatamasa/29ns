<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostLikeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_like_users')->truncate();

        $faker = Faker::create('ja_JP');

        $post_ids = DB::table('posts')->pluck('id');
        $user_ids = DB::table('users')->pluck('id');
        for($i = 0; $i < 1000; $i++){
            try {
                DB::table('post_like_users')->insert([
                    'post_id' => $faker->randomElement($post_ids),
                    'user_id' => $faker->randomElement($user_ids),
                ]);
            } catch (Exception $e) {
            }
        }
        // postsのlike_countを更新
        $sql = '
            update
                posts p inner join (select post_id, count(user_id) as like_count from post_like_users group by post_id) pls
                on p.id = pls.post_id
            set
                p.like_count = pls.like_count
            ';

        DB::update($sql);
    }
}
