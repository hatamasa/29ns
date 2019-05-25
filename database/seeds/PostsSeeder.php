<?php

use App\Services\ApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
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
        DB::table('posts')->truncate();

        $faker = Faker::create('ja_JP');

        $category = [
            Config::get("const.gnavi.category.yakiniku")[0]['category_s_code'],
            Config::get("const.gnavi.category.yakiniku")[1]['category_s_code'],
            Config::get("const.gnavi.category.yakitori")[0]['category_s_code'],
            Config::get("const.gnavi.category.yakitori")[1]['category_s_code'],
            Config::get("const.gnavi.category.steak")[0]['category_s_code'],
            Config::get("const.gnavi.category.hamburg")[0]['category_s_code'],
            Config::get("const.gnavi.category.hamburger")[0]['category_s_code'],
            Config::get("const.gnavi.category.other")[0]['category_s_code'],
            Config::get("const.gnavi.category.other")[1]['category_s_code'],
            Config::get("const.gnavi.category.other")[2]['category_s_code'],
        ];

        $options = [
            // 田町, 三田
            'areacode_m'   => 'AREAM2175',
            'category_s'   => implode(',', $category),
            'hit_per_page' => 100
        ];
        $result = (new ApiService())->callGnaviRestSearchApi($options);

        $user_ids  = DB::table('Users')->pluck('id');

        $data = [];
        for($i = 0; $i < 1000; $i++){
            $data[] = [
                'user_id'       => $faker->randomElement($user_ids),
                'shop_cd'       => $faker->randomElement($result['rest'])['id'],
                'score'         => $faker->numberBetween(0, 10),
                'visit_count'   => $faker->numberBetween(1, 5),
                'title'         => $faker->realText(100),
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
    }
}
