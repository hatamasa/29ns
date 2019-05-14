<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_tags')->truncate();

        $faker = Faker::create('ja_JP');

        $post_ids = DB::table('Posts')->pluck('id');
        $tag_ids  = DB::table('Tags')->pluck('id');

        for($i = 0; $i < 200; $i++){
            try {
                DB::table('post_tags')->insert([
                    'post_id'    => $faker->randomElement($post_ids),
                    'tag_id'     => $faker->randomElement($tag_ids),
                ]);
            } catch (Exception $e) {

            }
        }

    }
}
