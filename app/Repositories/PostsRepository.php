<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
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
     * @param int $page
     * @param int $user_id
     * @return \Illuminate\Support\Collection
     */
    public function getRecentlyList(int $limit, int $page = 1, int $user_id = null)
    {
        $sub = DB::table('post_like_users')
            ->select('post_id')
            ->where(['user_id' => Auth::id()]);

        $query = DB::table('posts as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoinSub($sub, 'plu', function ($join){
                $join->on('p.id', '=', 'plu.post_id');
            })
            ->join('shops as s', 'p.shop_cd', '=', 's.shop_cd')
            ->select(
                'p.id',
                'p.shop_cd',
                'p.title',
                'p.score',
                'p.img_url_1',
                'p.like_count',
                'p.comment_count',
                'p.created_at as post_created_at',
                DB::raw('CASE WHEN u.id is null THEN 0 ELSE u.id END user_id'),
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url',
                'u.sex as user_sex',
                DB::raw('CASE WHEN plu.post_id IS NOT NULL THEN 1 ELSE 0 END is_liked')
            )
            ->where([
                'p.is_deleted' => 0,
                's.is_deleted' => 0,
                'u.is_resigned' => 0
            ])
            ->orWhereNull('u.is_resigned')
            ->orderBy('p.id', 'desc')
            ->offset(($page-1) * $limit)
            ->limit($limit)
            ;

        if (! is_null($user_id)) {
            $query->where('u.id', $user_id);
        }

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
        $sub = DB::table('post_like_users')
            ->select('post_id')
            ->where(['user_id' => Auth::id()]);

        $query = DB::table('posts as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoinSub($sub, 'plu', function ($join){
                $join->on('p.id', '=', 'plu.post_id');
            })
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
                DB::raw('CASE WHEN u.id is null THEN 0 ELSE u.id END user_id'),
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url',
                'u.sex as user_sex',
                DB::raw('CASE WHEN plu.post_id IS NOT NULL THEN 1 ELSE 0 END is_liked')
            )
            ->where(['p.shop_cd' => $shop_cd, 'u.is_resigned' => 0])
            ->orWhereNull('u.is_resigned')
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
        $sub = DB::table('post_like_users')
            ->select('post_id')
            ->where(['user_id' => Auth::id()]);

        $query = DB::table('posts as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoinSub($sub, 'plu', function ($join){
                $join->on('p.id', '=', 'plu.post_id');
            })
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
                DB::raw('CASE WHEN u.id is null THEN 0 ELSE u.id END user_id'),
                'u.name as user_name',
                'u.thumbnail_url as user_thumbnail_url',
                'u.sex as user_sex',
                DB::raw('CASE WHEN plu.post_id IS NOT NULL THEN 1 ELSE 0 END is_liked')
            )
            ->where(['p.id' => $id, 'u.is_resigned' => 0])
            ->orWhereNull('u.is_resigned')
            ;

            return $query->first();
    }

    /**
     * 得点の合計値を取得する
     * @param string $shop_cd
     * @return \Illuminate\Database\Eloquent\Model|object|\Illuminate\Database\Query\Builder|NULL
     */
    public function getSumDiffScore(string $shop_cd)
    {
        $query = DB::table('posts')
            ->selectRaw('sum(score - 5) as score')
            ->where('shop_cd', $shop_cd)
            ->groupBy('shop_cd');

        return $query->first();
    }

}