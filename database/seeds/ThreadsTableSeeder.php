<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Threads')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');

        for($i = 0; $i < 10; $i++){
            try {
                DB::table('Threads')->insert([
                    'from_user_id' => $faker->randomElement($user_ids),
                    'to_user_id'   => $faker->randomElement($user_ids),
                ]);

            } catch (Exception $e) {

            }
        }

    }
}
