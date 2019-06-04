<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PostsRepository
{
    // ページ表示件数
    const TOP_LIST_LIMIT = 20;

    public function __construct()
    {
    }

    /**
     * 最近の投稿を取得する
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentlyList(int $limit, int $page = 1)
    {
        $query = DB::table('posts as p')
            ->select(
                'p.id',
                'p.shop_cd',
                'p.title',
                'p.score',
                'p.img_url_1',
                'p.like_count',
                'p.comment_count',
                'p.created_at as post_created_at',
                'u.id as user_id',
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url'
            )
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('p.is_deleted', 0)
            ->orderBy('p.id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
            ;

        return $query->get()->toArray();
    }

    /**
     * 店舗コードで取得する
     * @param string $shop_cd
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getListByShopCd(string $shop_cd, int $limit, int $page = 1)
    {
        $query = DB::table('Posts as p')
            ->select(
                'p.id',
                'p.shop_cd',
                'p.title',
                'p.score',
                'p.img_url_1',
                'p.like_count',
                'p.comment_count',
                'p.created_at as post_created_at',
                'u.id as user_id',
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url',
                'u.sex as user_sex'
            )
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('p.shop_cd', $shop_cd)
            ->orderBy('p.id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
            ;

        return $query->get()->toArray();
    }

    /**
     * IDで取得する
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|object|\Illuminate\Database\Query\Builder|NULL
     */
    public function getById(int $id)
    {
        $query = DB::table('Posts as p')
            ->select(
                'p.id',
                'p.shop_cd',
                'p.title',
                'p.contents',
                'p.score',
                'p.img_url_1',
                'p.img_url_2',
                'p.img_url_3',
                'p.like_count',
                'p.comment_count',
                'p.created_at as post_created_at',
                'u.id as user_id',
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url',
                'u.sex as user_sex'
            )
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('p.id', $id)
            ;

            return $query->first();
    }

}