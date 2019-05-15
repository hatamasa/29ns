<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post')->truncate();

        $faker = Faker::create('ja_JP');

        $group_ids = DB::table('Groups')->pluck('id');
        $user_ids  = DB::table('Users')->pluck('id');

        $data = [];
        for($i = 0; $i < 200; $i++){
            $data[] = [
                'user_id'    => $faker->randomElement($user_ids),
                'group_id'   => (($i+1)%3 == 0) ? null : $faker->randomElement($group_ids),
                'title'      => $faker->realText(30),
                'contents'   => $faker->realText(),
                'is_deleted' => (($i+1)%5 == 0) ? 1 : 0,
            ];
        }

        DB::table('Posts')->insert($data);
    }
}
