<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositories\ShopsRepository;
use App\Services\ApiService;

class InsertPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert posts to shops';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiService $apiService, ShopsRepository $shops)
    {
        parent::__construct();
        $this->ApiService = $apiService;
        $this->Shops = $shops;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("start InsertPostsCommand");

        $shops = DB::table('shops')->get();
        $progressBar = $this->output->createProgressBar(count($shops));

        $posts = [];

        DB::beginTransaction();
        try {
            foreach ($shops as $shop) {
                $this->processing();
                $posts = array_merge($posts, $this->createPostsData($shop->shop_cd));
                $progressBar->advance();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info("error!!");
            return;
        }

        DB::table('posts')->insert($posts);
        $progressBar->finish();
        $this->info("end InsertPostsCommand");
    }

    private function createPostsData($shop_cd)
    {
        $posts = [];
        try {
            $page = 0;
            do {
                $page++;
                $options = [
                    'hir_per_page' => 50,
                    "offset_page" => 1+50*($page-1),
                ];

                $logs = $this->ApiService->callGnaviPhotoSearchApi($options, $shop_cd);
                foreach ($logs['photo'] as $log) {
                    $posts[] = [
                        "user_id" => 0,
                        "shop_cd" => $shop_cd,
                        "score" => $log["total_score"]*2,
                        "visit_count" => 1,
                        "title" => $log["menu_name"].$log["category"]."/".$log["nickname"]."さん",
                        "contents" => $log["comment"],
                        "img_url_1" => $log["image_url"]["url_250"],
                    ];
                }

                $this->info("page: ".$page."/".ceil($logs['total_hit_count']/100));
            } while(ceil($logs['total_hit_count']/100) > $page);

            $post_count = DB::table("posts")->where(['shop_cd' => $shop_cd])->count();
            DB::table('shops')->where(['shop_cd' => $shop_cd])->update([
                'post_count' => $post_count,
                'score'      => $this->PostsService->calcScore($shop_cd, $post_count)
            ]);

        } catch (\Exception $e) {
            $this->info("error!!");
            throw new \Exception($e);
        }

        return $posts;
    }
}
