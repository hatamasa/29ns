<?php

namespace App\Http\Controllers;

use App\Services\PostsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    // 29ログ一覧表示件数
    const POSTS_LIST_LIMIT = 30;

    public function __construct(PostsService $postsService)
    {
        parent::__construct();
        $this->PostsService = $postsService;

        $this->middleware('auth')->except(['index']);
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        // 一覧を取得
        $posts = $this->PostsService->getList4RecentilyList(self::POSTS_LIST_LIMIT, $page);

        $count = DB::table('posts')->where('is_deleted', 0)->count();

        $posts = new LengthAwarePaginator(
            $posts,
            $count,
            self::POSTS_LIST_LIMIT,
            $request->page,
            ['path' => $request->url()]
            );

        return view('Posts.index', compact("posts"));
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
