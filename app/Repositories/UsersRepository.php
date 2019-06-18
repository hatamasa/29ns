<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UsersRepository
{
    public function __construct()
    {
    }

    /**
     * ユーザ詳細を取得する
     * @param int $id
     * @return array
     */
    public function getUserDetail(int $id)
    {
        $query = DB::table('users as u')
            ->select(
                "u.id",
                "u.name",
                "u.thumbnail_url",
                "u.sex",
                "u.birth_ym",
                "u.contents",
                "u.thumbnail_url",
                DB::raw("count(p.id) as posts_count"),
                DB::raw("count(distinct uf1.follow_user_id) as follow_count"),
                DB::raw("count(distinct uf2.user_id) as follower_count")
            )
            ->leftJoin('posts as p', 'p.user_id', '=', 'u.id')
            ->leftJoin('user_follows as uf1', 'uf1.user_id', '=', 'u.id')
            ->leftJoin('user_follows as uf2', 'uf2.follow_user_id', '=', 'u.id')
            ->where([
                'u.id'          => $id,
                'u.is_resigned' => 0
            ])
        ;

        return $query->first();
    }

}