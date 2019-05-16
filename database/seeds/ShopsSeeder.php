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
        DB:table('shops')->truncate();

        // 投稿されている店舗を取得
        $shops = DB::table('posts')
            ->select('shop_id')
            ->groupBy('shop_id')
            ->get();

        $data = [];
        foreach($shops as $shop){
            $data['shop_id']    = $shop['shop_id'];
            $data['score']      = 0;
            $data['post_count'] = 0;
            $data['like_count'] = 0;
        }

        DB:table('shops')->insert($data);
    }
}
