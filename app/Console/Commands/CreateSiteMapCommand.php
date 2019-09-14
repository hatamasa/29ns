<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

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

        $sitemap->add(URL::to('/'), date('Y-m-d'), '1.0', 'weekly');

        $sitemap->add(URL::to('/shops'), date('Y-m-d'), '1.0', 'weekly');

        $shops = DB::table('shops')->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
        foreach ($shops as $shop) {
            $sitemap->add(URL::to('/shops/'.$shop->shop_cd), date('Y-m-d'), '0.9', 'weekly');
        }

        $sitemap->add(URL::to('/shops/ranking'), date('Y-m-d'), '1.0', 'weekly');
        $sitemap->add(URL::to('/posts'), date('Y-m-d'), '1.0', 'weekly');

        $posts = DB::table('posts')->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post) {
            $sitemap->add(URL::to('/posts/'.$post->id), date('Y-m-d'), '0.9', 'weekly');
        }

        $sitemap->store('xml', 'sitemap');
        $this->info("CreateSiteMapCommand end");
    }
}