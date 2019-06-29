<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        $faker = Faker::create('ja_JP');

        $data = [];
        for($i = 0; $i < 30; $i++){
            $data[] = [
                'name'              => 'test'.($i+1),
                'email'             => 'test'.($i+1).'@29ns.com',
                'email_verified_at' => (($i+1)%9 == 0) ? null : $faker->dateTime,
                'sex'               => $faker->numberBetween(1, 2),
                'birth_ym'          => ($faker->dateTimeBetween('-100 years', 'now'))->format('Ym'),
                'contents'          => $faker->realText(),
                'thumbnail_url'     => $faker->imageUrl(400, 400),
                'password'          => Hash::make('test'.($i+1)),
                'remember_token'    => null,
                'is_resigned'       => (($i+1)%5 == 0) ? 1 : 0,
                'resigned_at'       => (($i+1)%5 == 0) ? date('Y-m-d H:i:s') : null,
            ];
        }

        DB::table('users')->insert($data);
    }
}
