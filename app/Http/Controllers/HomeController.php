<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostsService;
use App\Services\ShopsService;

/**
 * ホーム表示用コントローラ
 * @author hatamasa
 *
 */
class HomeController extends Controller
{
    // 肉ログ表示件数
    const POSTS_LIST_LIMIT = 5;
    // 人気店表示件数
    const SHOPS_LIST_LIMIT = 5;

    public function __construct(PostsService $postsService, ShopsService $shopsService)
    {
        parent::__construct();

        $this->PostsService = $postsService;
        $this->ShopsService = $shopsService;

        $this->middleware('verified')->except(['index']);
    }

    public function index(Request $request)
    {
        // リファラーに空をセット
        session(['referrers' => []]);
        // 最近の肉ログ一覧を取得
        $posts = $this->PostsService->getList4RecentilyList(self::POSTS_LIST_LIMIT);
        // 人気のお店を取得
        $shops = $this->ShopsService->getList4PopularityList(self::SHOPS_LIST_LIMIT);

        return view('home.index', compact('posts', 'shops'));
    }

}
