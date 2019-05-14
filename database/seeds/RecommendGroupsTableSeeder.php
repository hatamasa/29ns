<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RecommendGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recommend_groups')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');
        $group_ids  = DB::table('Groups')->pluck('id');

        foreach ($user_ids as $id) {
            for($i = 0; $i < 10; $i++){
                try {
                    DB::table('recommend_groups')->insert([
                        'user_id'        => $id,
                        'recommend_group_id' => $faker->randomElement($group_ids),
                        'is_joined'      => 0,
                    ]);
                } catch (Exception $e) {
                }
            }
        }

    }
}
