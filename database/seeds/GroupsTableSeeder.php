<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Groups')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');

        $data = [];
        for($i = 0; $i < 30; $i++){
            $data[] = [
                'owner_user_id' => $faker->randomElement($user_ids),
                'title'         => 'group'.($i+1),
                'description'   => $faker->realText(),
                'is_deleted'    => (($i+1)%5 == 0) ? 1 : 0,
            ];
        }

        DB::table('Groups')->insert($data);
    }
}
