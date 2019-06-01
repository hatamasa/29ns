<?php
namespace App\Services;

use App\Repositories\PostsRepository;

class PostsService extends Service
{
    public function __construct(PostsRepository $posts)
    {
        $this->Posts = $posts;
    }

    /**
     * 最近の29ログ一覧を取得する
     * @param int $id
     * @param number $page
     * @return object
     */
    public function getList4RecentilyList(int $limit, int $page = 1)
    {
        // 最近の投稿を取得
        $posts = $this->Posts->getRecentlyList($limit, $page);

        $result = [];
        foreach (array_chunk($posts, 10) as $chunk) {
            $shop_ids = [];
            foreach ($chunk as $post) {
                $shop_ids[] = $post->shop_cd;
            }
            $this->_log("shops: ".json_encode($shop_ids));
            // 投稿から店を10件毎に取得
            $tmp = (new ApiService())->callGnaviRestSearchApi(['id' => implode(',', $shop_ids)]);
            $result = array_merge($result, $tmp['rest']);
        }
        // 店舗の取得結果から投稿に必要な情報を取得する
        foreach ($result as $shop) {
            foreach ($posts as &$post) {
                if ($shop['id'] == $post->shop_cd) {
                    $post->shop_name     = $shop['name'];
                    $post->shop_img_url  = !empty($shop['image_url']['shop_image1']) ? $shop['image_url']['shop_image1'] : null;
                }
            }
        }

        return $posts;
    }

}