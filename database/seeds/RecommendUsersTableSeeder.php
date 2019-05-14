<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RecommendUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recommend_users')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');

        foreach ($user_ids as $id) {
            $user_ids_other = DB::table('Users')->where('id', '!=', $id)->pluck('id');
            for($i = 0; $i < 10; $i++){
                try {
                    DB::table('recommend_users')->insert([
                        'user_id'        => $id,
                        'recommend_user_id' => $faker->randomElement($user_ids_other),
                        'is_followed'    => 0,
                    ]);
                } catch (Exception $e) {
                }
            }
        }

    }
}
