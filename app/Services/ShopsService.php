<?php
namespace App\Services;

use App\Repositories\ShopsRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShopsService extends Service
{
    public function __construct(ShopsRepository $shops)
    {
        $this->Shops = $shops;
    }

    /**
     * パラメータからAPIリクエストに使用するオプションを作成する
     * @param array $params
     * @return array
     */
    public function makeOptions(array $params, int $limit)
    {
        $options = [];
        $search_condition = [];

        $options['hit_per_page'] = $limit;
        $options['offset_page']  = $params['page'] ?? 1;

        // エリア検索
        if (isset($params['areacode_l'])) {
            $options['areacode_l'] = $params['areacode_l'];
            $search_condition[] = Config::get("const.area_l")[$params['areacode_l']];
        }
        if (isset($params['areacode_m'])) {
            $options['areacode_m'] = $params['areacode_m'];
            $search_condition[] = DB::table('areas')->where("area_cd", $params['areacode_m'])->value("name");
        }

        // 駅検索
        if (isset($params['station_list'])) {
            foreach ($params['station_list'] as $station) {
                $options['freeword'][] = $station;
            }
            $options['freeword'] = implode(',', $options['freeword']);
            // or検索
            $options['freeword_condition'] = 2;
            if (count($params['station_list']) > 1) {
                $search_condition[] = $params['station_list'][0].' 他';
            } else {
                $search_condition[] = $params['station_list'][0];
            }
        }

        // フリーワード検索
        if (isset($params['keyword'])) {
            $keyword = str_replace([' ','　'], ',', trim(mb_convert_kana($params['keyword'], 'a', 'UTF-8')));
            if (!$keyword) {
                throw new \Exception('検索キーワードを入力してください');
            }
            $keyword_list = explode(',', $keyword);
            $keyword_list = array_filter($keyword_list, function ($val) {
                return !empty(trim($val));
            });
            if (count($keyword_list) > 10) {
                throw new \Exception('検索キーワードは10個までです。');
            }
            $options['freeword'] = implode(',', $keyword_list);
            if (count($keyword_list) > 1) {
                $search_condition[] = $keyword_list[0].' 他';
            } else {
                $search_condition[] = $keyword_list[0];
            }
        }

        return [$options, $search_condition];
    }

    /**
     * 人気のお店を取得する
     * @param int $limit
     * @param int $page
     */
    public function getList4PopularityList(int $limit, int $page = 1)
    {
        $shops = $this->Shops->getPopularityList($limit, $page);

        $result = [];
        foreach (array_chunk($shops, 10) as $chunk) {
            $shop_ids = [];
            foreach ($chunk as $shop) {
                $shop_ids[] = $shop->shop_cd;
            }
            $this->_log("shops: ".json_encode($shop_ids));
            // 投稿から店を10件毎に取得
            $tmp = (new ApiService())->callGnaviRestSearchApi(['id' => implode(',', $shop_ids)]);
            $result = array_merge($result, $tmp['rest']);
        }
        // 店舗の取得結果から投稿に必要な情報を取得する
        foreach ($result as $res_shop) {
            foreach ($shops as &$shop) {
                if ($res_shop['id'] == $shop->shop_cd) {
                    $shop->shop_name     = $res_shop['name'];
                    $shop->shop_img_url  = !empty($res_shop['image_url']['shop_image1']) ? $res_shop['image_url']['shop_image1'] : null;
                    $shop->line    = $res_shop['access']['line'];
                    $shop->station = $res_shop['access']['station'];
                    $shop->walk    = $res_shop['access']['walk'];
                    $shop->note    = $res_shop['access']['note'];
                    $shop->budget  = $res_shop['budget'];
                }
            }
        }

        return $shops;
    }

    /**
     * 店舗データにDBからスコアをマージする
     * @param array $shops
     * @return array
     */
    public function margeScore($shops)
    {
        $shop_cds = [];
        foreach ($shops as $shop) {
            $shop_cds[] = $shop['id'];
        }
        $shop_scores = $this->Shops->getListByShopCds($shop_cds);

        foreach ($shops as &$shop) {
            foreach ($shop_scores as $key => $shop_score) {
                if ($shop['id'] == $shop_score->shop_cd) {
                    $shop['score']      = $shop_score->score;
                    $shop['post_count'] = $shop_score->post_count;
                    $shop['like_count'] = $shop_score->like_count;
                    unset($shop_scores[$key]);
                    break;
                }
            }
        }

        return $shops;
    }

}