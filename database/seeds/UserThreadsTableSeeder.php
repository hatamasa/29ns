<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use PhpParser\Node\Stmt\TryCatch;

class UserThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_threads')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');
        $thread_ids = DB::table('Threads')->pluck('id');

        for($i = 0; $i < 50; $i++){
            try {
                DB::table('user_threads')->insert([
                    'user_id'     => $faker->randomElement($user_ids),
                    'thread_id'   => $faker->randomElement($thread_ids),
                    'is_resigned' => $faker->numberBetween(0, 1),
                ]);
            } catch (Exception $e) {
            }
        }

    }
}
