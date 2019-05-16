<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\ApiService;
use Faker\Factory as Faker;
use Symfony\Component\Console\Helper\Table;

class ShopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->truncate();

        // 投稿されている店舗を取得
        $shops = DB::table('posts')
            ->select('shop_cd')
            ->groupBy('shop_cd')
            ->get();

        $data = [];
        foreach($shops as $shop){
            $data[] = [
                'shop_cd'    => $shop->shop_cd,
                'score'      => 0,
                'post_count' => 0,
                'like_count' => 0,
            ];
        }

        DB::table('shops')->insert($data);

        // shopsのscore, post_countを更新
        $sql = '
            update
                shops s inner join (select shop_cd, count(id) as post_count, avg(score) as score from posts group by shop_cd) p
                on s.shop_cd = cast(p.shop_cd as char(7))
            set
                s.score = p.score,
                s.post_count = p.post_count
            ';

        DB::update($sql);
    }
}
