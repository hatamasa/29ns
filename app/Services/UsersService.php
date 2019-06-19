<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserLikeShopsRepository;
use App\Repositories\UserFollowsRepository;


class UsersService extends Service
{
    // ユーザページ投稿表示件数
    const USER_PAGE_LIST_LIMIT = 30;

    public function __construct(PostsService $postsService, ShopsService $shopsService, UserFollowsRepository $userFollows, UserLikeShopsRepository $userLikeShops)
    {
        parent::__construct();
        $this->PostsService = $postsService;
        $this->ShopsService = $shopsService;
        $this->UserFollows = $userFollows;
        $this->UserLikeShops = $userLikeShops;
    }

    /**
     * 選択されたリストからタブを表示する
     * @param Request $request
     * @param int $id
     * @param int $tab
     * @throws \Exception
     * @return array|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getList4TabArea($request, $id, $tab)
    {
        $page = $request->page ?? 1;

        switch ($tab) {
            case 1:// 投稿
                list($list, $count) = $this->getPosts($page, $id);
                break;
            case 2:// お気に入り
                list($list, $count) = $this->getLikeShops($page, $id);
                break;
            case 3:// フォロー
                list($list, $count) = $this->getFollows($page, $id);
                break;
            case 4:// フォロワー
                list($list, $count) = $this->getFollowers($page, $id);
                break;
            default:
                list($list, $count) = $this->getPosts($page, $id);
        }

        return new LengthAwarePaginator(
                $list,
                $count,
                self::USER_PAGE_LIST_LIMIT,
                $page,
                ['path' => $request->url()]
            );;
    }

    /**
     * タブエリアの投稿を取得する
     * @param int $page
     * @param int $id
     * @return Collection|int
     */
    private function getPosts($page, $id)
    {
        // 一覧を取得
        $posts = $this->PostsService->getList4RecentilyList(self::USER_PAGE_LIST_LIMIT, $page, $id);

        $count = DB::table('posts')->where([
                'is_deleted' => 0,
                'user_id'    => $id
            ])->count();

        return [$posts, $count];
    }

    /**
     * タブエリアのお気に入り店舗を取得する
     * @param int $page
     * @param int $id
     * @return Collection|int
     */
    private function getLikeShops($page, $id)
    {
        $user_like_shops = $this->UserLikeShops->getListByUserId(self::USER_PAGE_LIST_LIMIT, $page, $id);
        // APIから必要なデータを取得する
        $shops = $this->ShopsService->getAttrDataFromApi($user_like_shops);

        $count = DB::table('user_like_shops')->where([
            'user_id' => $id
        ])->count();

        return [$shops, $count];
    }

    /**
     * タブエリアのフォロー一覧を取得する
     * @param int $page
     * @param int $id
     * @return Collection|int
     */
    private function getFollows($page, $id)
    {
        $user_follows = $this->UserFollows->getListByUserId(self::USER_PAGE_LIST_LIMIT, $page, $id);

        $count = DB::table('user_follows')->where([
            'user_id' => $id
        ])->count();

        return [$user_follows, $count];
    }

    /**
     * タブエリアのフォロワー一覧を取得する
     * @param int $page
     * @param int $id
     * @return Collection|int
     */
    private function getFollowers($page, $id)
    {
        $user_follows = $this->UserFollows->getListByFollowUserId(self::USER_PAGE_LIST_LIMIT, $page, $id);

        $count = DB::table('user_follows')->where([
            'follow_user_id' => $id
        ])->count();

        return [$user_follows, $count];
    }
}