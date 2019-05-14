<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Users')->truncate();

        $faker = Faker::create('ja_JP');

        $data = [];
        for($i = 0; $i < 15; $i++){
            $data[] = [
                'name'              => 'test'.($i+1),
                'email'             => 'test'.($i+1).'@nrt.com',
                'email_verified_at' => $faker->dateTime,
                'password'          => Hash::make('test'.($i+1)),
                'remember_token'    => null,
                'is_resigned'       => (($i+1)%5 == 0) ? 1 : 0,
                'resigned_at'       => (($i+1)%5 == 0) ? date('Y-m-d H:i:s') : null,
            ];
        }

        DB::table('Users')->insert($data);
    }
}
