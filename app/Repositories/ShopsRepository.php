<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ShopsRepository
{
    private $Posts;

    public function __construct()
    {
    }

    /**
     * 人気の店一覧を取得する
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getPopularityList(int $limit, int $page = 1)
    {
        $query = DB::table('shops')
            ->select(
                'shop_cd',
                'score',
                'post_count',
                'like_count'
            )
            ->orderBy('score', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
            ;

        return $query->get()->toArray();
    }

    /**
     * idのリストで店舗を取得する
     * @param array $shop_ids
     * @return array
     */
    public function getListByShopCds(array $shop_cds)
    {
        $query = DB::table('shops')
            ->select(
                'shop_cd',
                'score',
                'post_count',
                'like_count'
            )
            ->whereIn('shop_cd', $shop_cds)
            ;

        return $query->get()->toArray();
    }

}