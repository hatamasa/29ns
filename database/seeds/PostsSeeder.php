<?php

use App\Services\ApiService;
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

        $options = [
            // 田町, 三田
            'areacode_m' => 'AREAM2175',
        ];
        $result = (new ApiService())->callGnaviRestSearchApi($options);

        $user_ids  = DB::table('Users')->pluck('id');

        $data = [];
        for($i = 0; $i < 100; $i++){
            $data[] = [
                'user_id'       => $faker->randomElement($user_ids),
                'shop_id'       => $faker->randomElement($result['rest'])['id'],
                'score'         => $faker->numberBetween(0, 10),
                'visit_count'   => $faker->numberBetween(1, 5),
                'title'         => $faker->realText(30),
                'contents'      => $faker->realText(),
                'img_url_1'     => $faker->imageUrl(),
                'img_url_2'     => $faker->imageUrl(),
                'img_url_3'     => $faker->imageUrl(),
                'like_count'    => 0,
                'comment_count' => 0,
                'is_deleted'    => (($i+1)%5 == 0) ? 1 : 0,
            ];
        }

        DB::table('Posts')->insert($data);

        // shopsのscore, post_countを更新
        $sql = '
            update
                shops s inner join (select shop_id, count(id) as post_count, average(score) as score from posts group by shop_id) p
                on s.id = p.shop_id
            set
                s.score = p.score,
                s.post_count = p.post_count
            ';

        DB::update($sql);
    }
}
