<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotFoundShopException;
use App\Repositories\ShopsRepository;
use App\Services\ApiService;
use App\Services\PostsService;

class InsertPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:posts {limit?} {offset?}';

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
    public function __construct(ApiService $apiService, PostsService $postsService, ShopsRepository $shops)
    {
        parent::__construct();
        $this->ApiService = $apiService;
        $this->PostsService = $postsService;
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
        $limit = $this->argument('limit') ?? 500;
        $offset = $this->argument('offset') ?? 0;
        $this->info("limit: ".$limit." offset: ".$offset);

        $posts = [];
        $shop_list = [];

        DB::beginTransaction();
        try {
            // 全店舗を10店舗ずつ口コミを取得する
            $cnt = 0;
            DB::table('shops')->orderBy('id')->offset($offset)
                ->chunk(10, function ($shops) use(&$posts, &$shop_list, $limit, $offset, &$cnt) {
                    // 指定件数を超えた場合はリターンする
                    if ($limit < $cnt+1) {
                        $this->info("return");
                        return false;
                    }
                    $this->info("------------- chunk ".($offset+$cnt+1)." ~ ".($offset+$cnt+10)."-----------------------");
                    $cnt += count($shops);

                    $shop_ids = [];
                    foreach ($shops as $shop) {
                        $shop_ids[] = $shop->shop_cd;
                    }
                    $this->info(implode(",", $shop_ids));

                    list($res_posts, $res_shops) = $this->createPostsData($shop_ids);
                    $posts = array_merge($posts, $res_posts);
                    $shop_list = array_merge($shop_list, $res_shops);
                });
            // マルチプルインサートで口コミを登録
            DB::table('posts')->insert($posts);

            $this->info("start shop post_count update");
            // 店舗の点数を更新
            foreach ($shop_list as $shop_cd) {
                $post_count = DB::table("posts")->where(['shop_cd' => $shop_cd])->count();
                DB::table('shops')->where(['shop_cd' => $shop_cd])->update([
                    'post_count' => $post_count,
                    'score'      => $this->PostsService->calcScore($shop_cd)
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info("error!!");
            $this->info($e->getMessage());
            $this->info($e->getTraceAsString());
            return;
        }

        $this->info("end InsertPostsCommand");
    }

    private function createPostsData(array $shop_ids)
    {
        $posts = [];
        $shops = [];
        $page = 0;

        do {
            $page++;
            $options = [
                'hir_per_page' => 50,
                "offset_page" => $page,
                "shop_id" => implode(",", $shop_ids)
            ];

            try {
                $logs = $this->ApiService->callGnaviPhotoSearchApi($options);
            } catch (NotFoundShopException $e) {
                $this->info("posts not found");
                break;
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
            $response = $logs['response'];
            foreach ($response as $key => $log) {
                // キーが数値のデータだけが口コミデータ
                if (!is_numeric($key)) {
                    continue;
                }
                $photo = $log['photo'];
                $shops[] = $photo["shop_id"];
                $posts[] = [
                    "user_id" => 0,
                    "shop_cd" => $photo["shop_id"],
                    "score" => ($photo["total_score"]??2.5)*2,
                    "visit_count" => 1,
                    "title" => ($photo["menu_name"]??'').$photo["category"]."/".$photo["nickname"]."さん",
                    "contents" => $photo["comment"]??'',
                    "img_url_1" => $photo["image_url"]["url_250"],
                    "created_at" => $photo["update_date"]
                ];
            }

            $this->info("page: ".$page."/".ceil($response['total_hit_count']/50));
        } while(ceil($response['total_hit_count']/50) > $page);

        return [$posts, $shops];
    }
}
