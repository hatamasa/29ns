<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ThreadMessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('thread_messages')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');
        $thread_ids = DB::table('Threads')->pluck('id');

        for($i = 0; $i < 100; $i++){
            try {
                DB::table('thread_messages')->insert([
                    'thread_id'   => $faker->randomElement($thread_ids),
                    'user_id'     => $faker->randomElement($user_ids),
                    'contents'    => $faker->realText(),
                ]);
            } catch (Exception $e) {
            }
        }

    }
}
