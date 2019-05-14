<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_profile')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');

        $data = [];
        foreach ($user_ids as $id) {
            $data[] = [
                'user_id'    => $id,
                'industry'   => $faker->randomElement(['IT', 'メーカー', '商社', '金融']),
                'company'    => $faker->company,
                'occupation' => $faker->randomElement(['営業', '技術', '経理', '総務', '人事']),
                'position'   => $faker->randomElement(['本部長', '部長', '課長', '一般']),
                'contents'   => $faker->realText(),
            ];
        }

        DB::table('user_profile')->insert($data);
    }
}
