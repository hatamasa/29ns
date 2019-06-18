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
        $query = DB::table('user_like_shops')
            ->where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
        ;

        return $query->get()->toArray();
    }

}