<?php
namespace App\Services;

use App\Repositories\PostsRepository;
use App\Repositories\ShopsRepository;

class ShopsService extends Service
{
    public function __construct(PostsRepository $posts, ShopsRepository $shops)
    {
        $this->Posts = $posts;
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

        $options['hit_per_page'] = $limit;
        $options['offset_page']  = $params['page'] ?? 1;

        // エリア検索
        if (isset($params['areacode_l'])) {
            $options['areacode_l'] = $params['areacode_l'];
        }
        if (isset($params['areacode_m'])) {
            $options['areacode_m'] = $params['areacode_m'];
        }

        // 駅検索
        if (isset($params['station_list'])) {
            foreach ($params['station_list'] as $station) {
                $options['freeword'][] = $station;
            }
            $options['freeword'] = implode(',', $options['freeword']);
            // or検索
            $options['freeword_condition'] = 2;
        }

        // フリーワード検索
        if (isset($params['keyword'])) {
            $keyword = str_replace([' ','　'], ',', trim(mb_convert_kana($params['keyword'], 'a', 'UTF-8')));
            if (explode(',', $keyword) > 10) {
                return '検索キーワードは10個までです。';
            }
            $options['freeword'] = $keyword;
        }

        return $options;
    }

    public function margeScore($shops)
    {
        // TODO: shops.scoreをAPI結果にマージする
    }

}