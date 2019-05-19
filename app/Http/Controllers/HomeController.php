<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PostsRepository;
use App\Repositories\ShopsRepository;

class HomeController extends Controller
{
    // 29ログ表示件数
    const POSTS_LIST_LIMIT = 5;
    // 人気店表示件数
    const SHOPS_LIST_LIMIT = 5;

    public function __construct(PostsRepository $posts, ShopsRepository $shops)
    {
        $this->Posts = $posts;
        $this->Shops = $shops;

        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // 認証ユーザIDを取得
        $user = Auth::user();
        // 29ログ一覧を取得
        $posts = $this->Posts->getList(self::POSTS_LIST_LIMIT);
        // 人気のお店を取得
        $shops = $this->Shops->getPopularity(self::SHOPS_LIST_LIMIT);

        return view('Home.index', compact('user', 'posts', 'shops'));
    }

    public function create()
    {
    }

    public function store()
    {
    }

    public function show()
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }

}
