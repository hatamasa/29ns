<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HomeService;

class HomeController extends Controller
{
    // 29ログ表示件数
    const POSTS_LIST_LIMIT = 5;
    // 人気店表示件数
    const SHOPS_LIST_LIMIT = 5;

    public function __construct(HomeService $HomeService)
    {
        parent::__construct();

        $this->HomeService = $HomeService;

        $this->middleware('auth')->except(['index']);
    }

    public function index(Request $request)
    {
        // 最近の29ログ一覧を取得
        $posts = $this->HomeService->getList4RecentilyList(self::POSTS_LIST_LIMIT);
        // 人気のお店を取得
        $shops = $this->HomeService->getList4PopularityList(self::SHOPS_LIST_LIMIT);

        return view('Home.index', compact('posts', 'shops'));
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
