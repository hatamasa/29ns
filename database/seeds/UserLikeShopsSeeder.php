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
        $shop_cds = DB::table('shops')->pluck('shop_cd');
        for($i = 0; $i < 1000; $i++){
            try {
                DB::table('user_like_shops')->insert([
                    'user_id' => $faker->randomElement($user_ids),
                    'shop_cd' => $faker->randomElement($shop_cds),
                ]);
            } catch (Exception $e) {
            }
        }
        // shopsのlike_countを更新
        $sql = '
            update
                shops s inner join (select shop_cd, count(user_id) as like_count from user_like_shops group by shop_cd) uls
                on s.shop_cd = cast(uls.shop_cd as char(7))
            set
                s.like_count = uls.like_count
            ';

        DB::update($sql);
    }
}
