<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_comments')->truncate();

        $faker = Faker::create('ja_JP');

        $post_ids = DB::table('Posts')->pluck('id');
        $user_ids  = DB::table('Users')->pluck('id');

        for($i = 0; $i < 300; $i++){
            try {
                DB::table('post_comments')->insert([
                    'post_id'    => $faker->randomElement($post_ids),
                    'user_id'    => $faker->randomElement($user_ids),
                    'contents'   => $faker->realText(),
                    'is_deleted' => $faker->numberBetween(0, 1),
                ]);
            } catch (Exception $e) {

            }
        }

    }
}
