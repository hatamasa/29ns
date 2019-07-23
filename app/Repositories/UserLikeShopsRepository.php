<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserLikeShopsRepository
{
    public function __construct()
    {
    }

    /**
     * user_idで取得する
     * @param int $id
     * @return array
     */
    public function getListByUserId(int $limit, $page, int $user_id)
    {
        $sub = DB::table('posts')
            ->select(
                'shop_cd'
            )
            ->where(['user_id' => $user_id])
            ->groupBy('shop_cd');

        $query = DB::table('user_like_shops as uls')
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                'uls.user_id',
                DB::raw('CASE WHEN p.shop_cd IS NOT NULL THEN 1 ELSE 0 END as is_posted')
            )
            ->join('shops as s', 'uls.shop_cd', '=', 's.shop_cd')
            ->leftJoinSub($sub, 'p', function($joins) {
                $joins->on('s.shop_cd', '=', 'p.shop_cd');
            })
            ->where('uls.user_id', $user_id)
            ->orderBy('uls.created_at', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
        ;

        return $query->get()->toArray();
    }

}