<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
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
        $sub = DB::table('user_follows')->where(['user_id' => Auth::id()]);

        $query = DB::table('user_follows as uf')
            ->select(
                DB::raw('CASE WHEN uf2.user_id IS NOT NULL THEN 1 ELSE 0 END is_followed'),
                'uf.follow_user_id',
                'u.id',
                'u.name',
                'u.thumbnail_url',
                'u.sex'
            )
            ->join('users as u', 'u.id', '=', 'uf.follow_user_id')
            ->leftJoinSub($sub, 'uf2', function ($join) {
                $join->on('uf.follow_user_id', '=', 'uf2.follow_user_id');
            })
            ->where([
                'uf.user_id' => $user_id,
                'u.is_resigned' => 0
            ])
            ->orderBy('uf.follow_user_id', 'desc')
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
        $sub = DB::table('user_follows')->where(['user_id' => $user_id]);

        $query = DB::table('user_follows as uf')
            ->select(
                DB::raw('CASE WHEN uf2.user_id IS NOT NULL THEN 1 ELSE 0 END is_followed'),
                'uf.follow_user_id',
                'u.id',
                'u.name',
                'u.thumbnail_url',
                'u.sex'
            )
            ->join('users as u', 'u.id', '=', 'uf.user_id')
            ->leftJoinSub($sub, 'uf2', function ($join) {
                $join->on('uf.user_id', '=', 'uf2.follow_user_id');
            })
            ->where([
                'uf.follow_user_id' => $user_id,
                'u.is_resigned' => 0
            ])
            ->orderBy('uf.user_id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
            ;

        return $query->get()->toArray();
    }

}