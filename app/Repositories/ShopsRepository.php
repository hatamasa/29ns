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
        $sub1 = DB::table('user_like_shops')
            ->where(['user_id' => Auth::id()]);

        $sub2 = DB::table('posts')
            ->select('shop_cd')
            ->where(['user_id' => Auth::id()])
            ->groupBy('shop_cd');

        $query = DB::table('shops as s')
            ->leftJoinSub($sub1, 'uls', function($joins) {
                $joins->on('s.shop_cd', '=', 'uls.shop_cd');
            })
            ->leftJoinSub($sub2, 'p', function($joins) {
                $joins->on('s.shop_cd', '=', 'p.shop_cd');
            })
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                DB::raw('CASE WHEN p.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_posted'),
                DB::raw('CASE WHEN uls.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_liked')
            )
            ->where('is_deleted', 0)
            ->orderBy('s.score', 'desc')
            ->orderBy('s.post_count', 'desc')
            ->orderBy('s.like_count', 'desc')
            ->orderBy('s.updated_at', 'asc')
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
        $sub1 = DB::table('user_like_shops')
            ->select('shop_cd')
            ->where(['user_id' => Auth::id()])
            ->whereIn('shop_cd', $shop_cds);

        $sub2 = DB::table('posts')
            ->select('shop_cd')
            ->where(['user_id' => Auth::id()])
            ->groupBy('shop_cd');

        $query = DB::table('shops as s')
            ->leftJoinSub($sub1, 'uls', function($joins) {
                $joins->on('s.shop_cd', '=', 'uls.shop_cd');
            })
            ->leftJoinSub($sub2, 'p', function($joins) {
                $joins->on('s.shop_cd', '=', 'p.shop_cd');
            })
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                DB::raw('CASE WHEN p.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_posted'),
                DB::raw('CASE WHEN uls.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_liked')
            )->whereIn('s.shop_cd', $shop_cds);

        return $query->get()->toArray();
    }

    /**
     * 行った、お気に入り店舗取得
     * @param int $limit
     * @param int $page
     * @param int $user_id
     * @param int $filter
     * @return array
     */
    public function getPostedAndLikedList(int $limit, $page, int $user_id, $filter)
    {
        $query = $this->getPostedAndLikedListQuery($limit, $page, $user_id, $filter);
        return $query->get()->toArray();
    }

    /**
     * 行った、お気に入り店舗件数取得
     * @param int $limit
     * @param int $page
     * @param int $user_id
     * @param int $filter
     * @return number
     */
    public function getPostedAndLikedListCount(int $limit, $page, int $user_id, $filter)
    {
        $query = $this->getPostedAndLikedListQuery($limit, $page, $user_id, $filter);
        return $query->count();
    }
    /**
     * 行った、お気に入り店舗取得のクエリを返す
     * @param int $limit
     * @param int $page
     * @param int $user_id
     * @param int $filter
     * @return \Illuminate\Database\Query\Builder
     */
    private function getPostedAndLikedListQuery(int $limit, $page, int $user_id, $filter)
    {
        $sub1 = DB::table('user_like_shops')
            ->select('shop_cd', 'created_at')
            ->where(['user_id' => $user_id]);

        $sub2 = DB::table('posts')
            ->select('shop_cd', DB::raw('MAX(created_at) as created_at'))
            ->where(['user_id' => $user_id])
            ->groupBy('shop_cd');

        $query = DB::table('shops as s')
            ->leftJoinSub($sub1, 'uls', function($joins) {
                $joins->on('s.shop_cd', '=', 'uls.shop_cd');
            })
            ->leftJoinSub($sub2, 'p', function($joins) {
                $joins->on('s.shop_cd', '=', 'p.shop_cd');
            })
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                DB::raw('CASE WHEN p.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_posted'),
                DB::raw('CASE WHEN uls.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_liked'),
                DB::raw('CASE WHEN uls.created_at IS NOT NULL THEN uls.created_at ELSE p.created_at END as created_at')
            )
            ->where(function($q1) use($user_id) {
                $q1->whereExists(function($q2) use($user_id) {
                    $q2->select(DB::raw('1'))
                        ->from('user_like_shops')
                        ->whereRaw('shop_cd = s.shop_cd')
                        ->where('user_id', $user_id);
                });

                $q1->orWhereExists(function($q2) use($user_id) {
                    $q2->select(DB::raw('1'))
                        ->from('posts')
                        ->whereRaw('shop_cd = s.shop_cd')
                        ->where('user_id', $user_id);
                });
            })
            ->where('s.is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit);

            switch ($filter) {
                case 1:
                    $query->whereRaw('p.shop_cd IS NOT NULL');
                    break;
                case 2:
                    $query->whereRaw('uls.shop_cd IS NOT NULL');
                    break;
            }

            return $query;
    }



}