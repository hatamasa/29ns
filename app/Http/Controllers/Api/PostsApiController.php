<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PostsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsApiController extends Controller
{
    private $PostsService;

    public function __construct(PostsService $posts_service)
    {
        $this->PostsService = $posts_service;

        $this->middleware('auth');
    }

    /**
     * 投稿を取得する
     * @param Request $request
     * @return object
     */
    public function getPostList(Request $request)
    {
        $result = [];
        // 一覧を取得
        $posts = $this->PostsService->getList4HomeRecentlyList(5);

        $result['posts'] = $posts;
        $result['return_code'] = 0;

        return $result;
    }

}
