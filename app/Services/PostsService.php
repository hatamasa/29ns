<?php
namespace App\Services;

use App\Repositories\PostsRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PostsService extends Service
{
    public function __construct(PostsRepository $posts, ApiService $apiService)
    {
        $this->Posts = $posts;
        $this->ApiService = $apiService;
    }

    /**
     * 投稿時のバリデーションを行う
     * @param array $params
     * @return boolean
     */
    public  function validateStore($params)
    {
        if (!isset($params['score']) || !isset($params['visit_count'])) {
            $this->_log("score or visit_count not found.", "error");
            return false;
        }
        if (!isset($params['title']) || strlen($params['title']) > 100) {
            return false;
        }
        if (!isset($params['contents']) || strlen($params['contents'] > 1000)) {
            return false;
        }
        if (!isset($params['files'])) {
            return false;
        }

        return true;
    }

    /**
     * 最近の肉ログ一覧を取得する
     * @param int $id
     * @param int $page
     * @param int $user_id
     * @return object
     */
    public function getList4RecentlyList(int $limit, int $page = 1, int $user_id = null)
    {
        // 最近の投稿を取得
        $posts = $this->Posts->getRecentlyList($limit, $page, $user_id);

        $result = [];
        foreach (array_chunk($posts, 10) as $chunk) {
            $shop_ids = [];
            foreach ($chunk as $post) {
                $shop_ids[] = $post->shop_cd;
            }
            $this->_log("shops: ".json_encode($shop_ids));
            // 投稿から店を10件毎に取得
            $tmp = $this->ApiService->callGnaviRestSearchApi(['id' => implode(',', $shop_ids)]);
            $result = array_merge($result, $tmp['rest']);
        }
        // 店舗の取得結果から投稿に必要な情報を取得する
        foreach ($posts as $key => &$post) {
            $post_exists = false;
            foreach ($result as $shop) {
                if ($post->shop_cd == $shop['id']) {
                    $post->shop_name     = $shop['name'];
                    $post->shop_img_url  = !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : null;
                    $post_exists = true;
                    break;
                }
            }
            // 投稿に対する店舗が取得出来なかった場合
            if (! $post_exists) {
                unset($posts[$key]);
            }
        }

        return $posts;
    }

    /**
     * [ホーム]最近の肉ログ一覧を取得する
     * @param int $id
     * @return object
     */
    public function getList4HomeRecentlyList(int $limit)
    {
        // 最近の投稿を取得
        $posts = $this->Posts->getRecentlyList($limit * 2);
        $shop_ids = [];
        foreach ($posts as $post) {
            $shop_ids[] = $post->shop_cd;
        }
        // Apiキャッシュを取得
        $result = $this->getRestApiCache($shop_ids, $limit * 2);
        // 店舗の取得結果から投稿に必要な情報を取得する
        $result_posts = [];
        foreach ($posts as $post) {
            $resigned_shop_cd = $post->shop_cd;
            foreach ($result as $shop) {
                if ($post->shop_cd == $shop['id']) {
                    $obj = $post;
                    $obj->shop_name = $shop['name'];
                    $obj->shop_img_url = !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : null;
                    $result_posts[] = $obj;
                    $resigned_shop_cd = null;
                    break;
                }
            }
            // 店舗がAPIから取得出来なかった場合はDBを更新する
            if (! is_null($resigned_shop_cd)) {
                DB::table('shops')->where(['shop_cd' => $resigned_shop_cd])->update(['is_deleted' => 1]);
            }
            if (count($result_posts) == $limit) {
                break;
            }
        }

        return $result_posts;
    }

    /**
     * ホーム最近の投稿の店舗キャッシュ取得と作成する
     * @param array $shop_ids
     * @param init $limit
     * @return array|mixed|mixed
     */
    private function getRestApiCache(array $shop_ids, $limit)
    {
        $shops = [];
        $json_path = storage_path('app/'.Config::get('const.home.recently_post_shops_json'));
        if (file_exists($json_path) ) {
            // キャッシュ有効
            $shops = json_decode(file_get_contents($json_path), true);
            if ($shops['shop_ids'] == implode('_', $shop_ids)) {
                unset($shops['shop_ids']);
                return $shops;
            }
        }
        // キャッシュがなかったり店舗が異なる場合は取得する
        $tmp = $this->ApiService->callGnaviRestSearchApi(['id' => implode(',', $shop_ids)]);
        $shops = $tmp['rest'];

        // apiから全部店舗を取得できてない場合は、件数を埋めるまで取得する
        if (count($shops) != $limit) {
            $i = 2;
            while (count($shops) < $limit) {
                // 再度取得
                $posts = $this->Posts->getRecentlyList($limit, $i);
                $tmp_ids = [];
                foreach ($posts as $post) {
                    $tmp_ids[] = $post->shop_cd;
                }
                $tmp = $this->ApiService->callGnaviRestSearchApi(['id' => implode(',', $tmp_ids)]);
                foreach ($tmp['rest'] as $rest) {
                    $shops[] = $rest;
                    if (count($shops) == $limit) {
                        break;
                    }
                }
                $i++;
            }
            // idの詰め替え
            $shop_ids = [];
            foreach ($shops as $shop) {
                $shop_ids[] =  $shop['id'];
            }
        }

        $shops['shop_ids'] = implode('_', $shop_ids);
        file_put_contents($json_path, json_encode($shops));
        unset($shops['shop_ids']);

        return $shops;
    }

    /**
     * idでDBとAPIから表示用の投稿を取得する
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|object|\Illuminate\Database\Query\Builder|NULL
     */
    public function getPostById(int $id)
    {
        $post = $this->Posts->getById($id);
        if (!$post) {
            return false;
        }

        $result = $this->ApiService->callGnaviRestSearchApi(['id' => $post->shop_cd]);
        $shop = $result['rest'][0];

        $post->shop_name    = $shop['name'];
        $post->shop_img_url = !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : null;

        return $post;
    }

    /**
     * 点数の計算ロジック
     * 投稿が5件以下は5点との差分を変換して計算
     * @param string $shop_cd
     * @param bool $is_add
     * @return NULL|number|mixed
     */
    public function calcScore(string $shop_cd)
    {
        $result = null;
        $user_count = DB::table("posts")->where('shop_cd', $shop_cd)->where('user_id', '!=', 0)->count();
        if ($user_count > 5) {
            // 5件を超えていたら平均で算出する
            $result = DB::table('posts')->where(['shop_cd' => $shop_cd])->avg('score');
        } else {
            // 5件を超えていない場合は計算ロジックで変換して加算する
            $sum_diff_score_29ns = $this->Posts->getSumDiffScore29ns($shop_cd);
            $sum_diff_score_gnavi = $this->Posts->getSumDiffScoreGnavi($shop_cd);
            $result = 5 + ($sum_diff_score_29ns->score ?? 0) / 20 + ($sum_diff_score_gnavi->score ?? 0) / 20;
        }

        return $result;
    }

}