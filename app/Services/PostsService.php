<?php
namespace App\Services;

use App\Repositories\PostsRepository;

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
    public function getList4RecentilyList(int $limit, int $page = 1, int $user_id = null)
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

}