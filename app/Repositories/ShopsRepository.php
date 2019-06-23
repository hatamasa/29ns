<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
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
        $sub = DB::table('user_like_shops')
            ->where(['user_id' => Auth::id()]);

        $query = DB::table('shops as s')
            ->leftJoinSub($sub, 'uls', function($joins) {
                $joins->on('s.shop_cd', '=', 'uls.shop_cd');
            })
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                DB::raw('CASE WHEN uls.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_liked')
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
        $sub = DB::table('user_like_shops')
            ->where(['user_id' => Auth::id()]);

        $first = DB::table('shops')
            ->select(
                'shop_cd as shop_cd',
                'score as score',
                'post_count as post_count',
                'like_count as like_count',
                DB::raw('NULL as is_liked')
            )
            ->whereIn('shop_cd', $shop_cds);

        $shops = DB::table('user_like_shops')
            ->select(
                'shop_cd',
                DB::raw('NULL'),
                DB::raw('NULL'),
                DB::raw('NULL'),
                DB::raw('CASE WHEN shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_liked')
                )
            ->whereIn('shop_cd', $shop_cds)
            ->unionAll($first);

        return $shops->get()->toArray();
    }

}