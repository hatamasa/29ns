<?php
namespace App\Services;

use App\Repositories\ShopsRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ShopsService extends Service
{
    public function __construct(ShopsRepository $shops, ApiService $apiService)
    {
        $this->Shops = $shops;
        $this->ApiService = $apiService;

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
     * @return Collection
     */
    public function getList4PopularityList(int $limit, int $page = 1)
    {
        $shops = $this->Shops->getPopularityList($limit, $page);

        return $this->getAttrDataFromApi($shops);
    }

    /**
     * Apiから表示に必要なデータを取得する
     * @param Collection $shops
     * @return Collection
     */
    public function getAttrDataFromApi($shops)
    {
        $result = [];
        foreach (array_chunk($shops, 10) as $chunk) {
            $shop_ids = [];
            foreach ($chunk as $shop) {
                $shop_ids[] = $shop->shop_cd;
            }
            $this->_log("shops: ".json_encode($shop_ids));
            // 投稿から店を10件毎に取得
            $tmp = $this->ApiService->callGnaviRestSearchApi(['id' => implode(',', $shop_ids)]);
            $result = array_merge($result, $tmp['rest']);
        }
        // 店舗の取得結果から投稿に必要な情報を取得する
        foreach ($shops as $key => &$shop) {
            $resigned_shop_cd = $shop->shop_cd;
            foreach ($result as $res_shop) {
                if ($shop->shop_cd == $res_shop['id']) {
                    $shop->shop_name     = $res_shop['name'];
                    $shop->shop_img_url  = !empty($res_shop['image_url']['shop_image1']) ? $res_shop['image_url']['shop_image1'] : null;
                    $shop->line    = $res_shop['access']['line'];
                    $shop->station = $res_shop['access']['station'];
                    $shop->walk    = $res_shop['access']['walk'];
                    $shop->note    = $res_shop['access']['note'];
                    $shop->budget  = $res_shop['budget'];
                    $resigned_shop_cd = null;
                    break;
                }
            }
            // 店舗がAPIから取得出来なかった場合は返却から解除して、DBを更新する
            if (! is_null($resigned_shop_cd)) {
                unset($shops[$key]);
                DB::table('shops')->where(['shop_cd' => $resigned_shop_cd])->update(['is_deleted' => 1]);
            }
        }

        return $shops;
    }

    /**
     * [ホーム]人気のお店を取得する
     * @param int $limit
     * @return Collection
     */
    public function getList4HomePopularityList(int $limit)
    {
        $shops = $this->Shops->getPopularityList($limit);
        $shop_ids = [];
        foreach ($shops as $shop) {
            $shop_ids[] = $shop->shop_cd;
        }
        // Apiキャッシュを取得
        $result = $this->getRestApiCache($shop_ids, $limit);
        // 店舗の取得結果から投稿に必要な情報を取得する
        $result_shops = [];
        foreach ($shops as $shop) {
            $resigned_shop_cd = $shop->shop_cd;
            foreach ($result as $res_shop) {
                if ($shop->shop_cd == $res_shop['id']) {
                    $obj = $shop;
                    $obj->shop_name     = $res_shop['name'];
                    $obj->shop_img_url  = !empty($res_shop['image_url']['shop_image1']) ? $res_shop['image_url']['shop_image1'] : null;
                    $obj->line    = $res_shop['access']['line'];
                    $obj->station = $res_shop['access']['station'];
                    $obj->walk    = $res_shop['access']['walk'];
                    $obj->note    = $res_shop['access']['note'];
                    $obj->budget  = $res_shop['budget'];
                    $result_shops[] = $obj;
                    $resigned_shop_cd = null;
                    break;
                }
            }
            // 店舗がAPIから取得出来なかった場合は返却から解除して、DBを更新する
            if (! is_null($resigned_shop_cd)) {
                DB::table('shops')->where(['shop_cd' => $resigned_shop_cd])->update(['is_deleted' => 1]);
            }
            if (count($result_shops) == $limit) {
                break;
            }
        }

        return $result_shops;
    }

    /**
     * ホーム人気店舗の店舗キャッシュ取得と作成する
     * @param array $shop_ids
     * @param init $limit
     * @return array|mixed|mixed
     */
    private function getRestApiCache(array $shop_ids, $limit)
    {
        $shops = [];
        $json_path = storage_path('app/'.Config::get('const.home.shop_ranking_shops_json'));
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
                $tmp_shops = $this->Shops->getPopularityList($limit, $i);
                $tmp_ids = [];
                foreach ($tmp_shops as $shop) {
                    $tmp_ids[] = $shop->shop_cd;
                }
                $tmp = $this->ApiService->callGnaviRestSearchApi(['id' => implode(',', $tmp_ids)]);
                foreach ($tmp['rest'] as $rest) {
                    $shops[] = $rest;
                    $shop_ids[] = $rest['id'];
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
     * shop_cdでAPIとDBから店舗を取得する
     * @param string $shop_cd
     * @return array
     */
    public function getShopByCd(string $shop_cd)
    {
        // 店舗を取得
        $shops = $this->ApiService->callGnaviRestSearchApi(['id' => $shop_cd]);
        if (count($shops) == 0) {
            return [];
        }
        $this->checkShopsRegist($shops['rest']);

        $shop = $this->margeAttr($shops['rest'])[0];

        return $shop;
    }

    /**
     * 店舗がDBにない場合は登録する
     * @param array $shops
     */
    private function checkShopsRegist(array $shops)
    {
        $data = [];
        foreach ($shops as $shop) {
            $count = DB::table('shops')->where('shop_cd', $shop['id'])->count();
            if (! $count) {
                $data[] = [
                    'shop_cd' => $shop['id'],
                    'score' => 0,
                    'post_count' => 0,
                    'like_count' => 0
                ];
            }
        }
        DB::table('shops')->insert($data);
    }

    /**
     * 店舗データにDBから情報をマージする
     * @param array $shops
     * @return array
     */
    public function margeAttr(array $shops)
    {
        $shop_cds = [];
        foreach ($shops as $shop) {
            $shop_cds[] = $shop['id'];
        }
        $db_shops = $this->Shops->getListByShopCds($shop_cds);

        foreach ($shops as &$shop) {
            foreach ($db_shops as $key => $db_shop) {
                if ($shop['id'] == $db_shop->shop_cd) {
                    $shop['score']      = $db_shop->score ?? null;
                    $shop['post_count'] = $db_shop->post_count ?? 0;
                    $shop['like_count'] = $db_shop->like_count ?? 0;
                    $shop['is_posted']  = $db_shop->is_posted;
                    $shop['is_liked']   = $db_shop->is_liked;
                    unset($db_shops[$key]);
                    break;
                }
            }
        }

        return $shops;
    }

}