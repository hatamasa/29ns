<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GroupUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_users')->truncate();

        $faker = Faker::create('ja_JP');

        $group_ids = DB::table('Groups')->pluck('id');
        $user_ids  = DB::table('Users')->pluck('id');

        for($i = 0; $i < 100; $i++){
            try {
                DB::table('group_users')->insert([
                    'group_id'   => $faker->randomElement($group_ids),
                    'user_id'    => $faker->randomElement($user_ids),
                ]);
            } catch (Exception $e) {

            }
        }
    }
}
