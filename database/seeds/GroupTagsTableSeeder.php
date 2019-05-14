<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GroupTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_tags')->truncate();

        $faker = Faker::create('ja_JP');

        $group_ids = DB::table('Groups')->pluck('id');
        $tag_ids  = DB::table('Tags')->pluck('id');

        for($i = 0; $i < 50; $i++){
            try {
                DB::table('group_tags')->insert([
                    'group_id'   => $faker->randomElement($group_ids),
                    'tag_id'     => $faker->randomElement($tag_ids),
                ]);
            } catch (Exception $e) {

            }
        }
    }
}
