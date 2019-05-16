<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserLikeShopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_like_shops')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids = DB::table('Users')->pluck('id');
        $shop_ids = DB::table('shops')->pluck('id');
        for($i = 0; $i < 1000; $i++){
            try {
                DB::table('user_like_shops')->insert([
                    'user_id' => $faker->randomElement($user_ids),
                    'shop_id' => $faker->randomElement($shop_ids),
                ]);
            } catch (Exception $e) {
            }
        }
        // shopsのlike_countを更新
        $sql = '
            update
                shops s inner join (select shop_id, count(user_id) as like_count from user_like_shops group by shop_id) uls
                on s.id = uls.shop_id
            set
                s.like_count = uls.like_count
            ';

        DB::update($sql);
    }
}
