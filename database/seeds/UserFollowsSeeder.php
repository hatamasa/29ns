<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserFollowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_follows')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');

        foreach ($user_ids as $id) {
            $user_ids_other = DB::table('Users')->where('id', '!=', $id)->pluck('id');
            for($i = 0; $i < 10; $i++){
                try {
                    DB::table('user_follows')->insert([
                        'user_id'        => $id,
                        'follow_user_id' => $faker->randomElement($user_ids_other),
                    ]);
                } catch (Exception $e) {
                }
            }
        }

    }
}
