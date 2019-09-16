<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateSiteMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create sitemap.xml';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("CreateSiteMapCommand start");
        $sitemap = App::make("sitemap");

        // ホーム
        $sitemap->add(url('/'), date('Y-m-d'), '1.0', 'daily');
        // 沿線一覧
        $sitemap->add(url('/search/line_company'), date('Y-m-d'), '1.0', 'monthly');
        foreach (Config::get('const.line_company') as $key => $val) {
            // 駅一覧
            $sitemap->add(url("/search/station/{$key}"), date('Y-m-d'), '1.0', 'monthly');
        }
        // エリア一覧
        $sitemap->add(url('/search/area'), date('Y-m-d'), '1.0', 'monthly');
        // 店舗一覧(エリアL)
        $area_l_cds = DB::table('areas')->groupBy('area_l_cd')->pluck('area_l_cd');
        foreach ($area_l_cds as $area_l_cd) {
            $sitemap->add(url("/shops?areacode_l={$area_l_cd}"), date('Y-m-d'), '1.0', 'daily');
        }
        // 店舗一覧(エリアM)
        $area_m_cds = DB::table('areas')->pluck('area_cd');
        foreach ($area_m_cds as $area_m_cd) {
            $sitemap->add(url("/shops?areacode_m={$area_m_cd}"), date('Y-m-d'), '1.0', 'daily');
        }
        // 店舗一覧(駅)
        $station_names = DB::table('stations')->pluck('name');
        foreach ($station_names as $station_name) {
            $sitemap->add(url("/shops?station_list[]={$station_name}"), date('Y-m-d'), '1.0', 'daily');
        }
        // 店舗詳細
        $shops = DB::table('shops')->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
        foreach ($shops as $shop) {
            $sitemap->add(url("/shops/{$shop->shop_cd}"), date('Y-m-d'), '0.9', 'daily');
        }
        // 店舗一覧(ランキング)
        $sitemap->add(url('/shops/ranking'), date('Y-m-d'), '1.0', 'daily');
        // 投稿一覧
        $sitemap->add(url('/posts'), date('Y-m-d'), '1.0', 'daily');
        // 投稿詳細
        $posts = DB::table('posts')->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post) {
            $sitemap->add(url("/posts/{$post->id}"), date('Y-m-d'), '0.9', 'daily');
        }

        $sitemap->store('xml', 'sitemap');
        $this->info("CreateSiteMapCommand end");
    }
}