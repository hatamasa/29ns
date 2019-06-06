<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PostCommentsRepository
{
    public function __construct()
    {
    }

    /**
     * post_idで取得する
     * @param int $id
     * @return array
     */
    public function getListByPostId(int $id)
    {
        $query = DB::table('post_comments as pc')
            ->select(
                "pc.id",
                "pc.contents",
                "pc.user_id",
                "pc.created_at",
                "u.name",
                "u.thumbnail_url",
                "u.sex"
            )
            ->join('users as u', 'pc.user_id', '=', 'u.id')
            ->where('pc.post_id', $id)
            ->orderBy('pc.id', 'asc')
        ;

        return $query->get()->toArray();
    }

}