<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Tags')->truncate();

        $faker = Faker::create('ja_JP');

        $user_ids  = DB::table('Users')->pluck('id');
        $tags = ['FP', 'AP', 'ITパスポート', 'AI', 'プログラミング', '機械学習', 'ディープラーニング'];

        $data = [];
        foreach ($tags as $tag) {
            $data[] = [
                'created_user_id' => $faker->randomElement($user_ids),
                'name'            => $tag,
                'type'            => $faker->numberBetween(1, 2),
                'is_deleted'      => 0,
            ];
        }

        DB::table('Tags')->insert($data);
    }
}
