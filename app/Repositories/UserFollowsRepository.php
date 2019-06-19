<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserFollowsRepository
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
        $query = DB::table('user_follows')
            ->where('user_id', $user_id)
            ->orderBy('follow_user_id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
        ;

        return $query->get()->toArray();
    }

    /**
     * follow_user_idで取得する
     * @param int $id
     * @return array
     */
    public function getListByFollowUserId(int $limit, $page, int $user_id)
    {
        $query = DB::table('user_follows')
        ->where('follow_user_id', $user_id)
        ->orderBy('user_id', 'desc')
        ->offset(($page-1) * $limit)
        ->limit($limit)
        ;

        return $query->get()->toArray();
    }

}