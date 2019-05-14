<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_requests')->truncate();

        $faker = Faker::create('ja_JP');

        $post_ids = DB::table('Posts')->pluck('id');
        $request_ids  = DB::table('Requests')->pluck('id');

        for($i = 0; $i < 300; $i++){
            try {
                DB::table('post_requests')->insert([
                    'post_id'    => $faker->randomElement($post_ids),
                    'request_id' => $faker->randomElement($request_ids),
                ]);
            } catch (Exception $e) {

            }
        }
    }
}
