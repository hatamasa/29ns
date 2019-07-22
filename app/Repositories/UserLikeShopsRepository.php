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
        $query = DB::table('user_like_shops as uls')
            ->select(
                's.shop_cd',
                's.score',
                's.post_count',
                's.like_count',
                'uls.user_id'
            )
            ->join('shops as s', 'uls.shop_cd', '=', 's.shop_cd')
            ->where('uls.user_id', $user_id)
            ->orderBy('uls.created_at', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
        ;

        return $query->get()->toArray();
    }

}