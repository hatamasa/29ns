<?php

namespace App\Http\Controllers;

use App\Services\PostsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    private $PostsService;

    public function __construct(PostsService $posts_service)
    {
        $this->PostsService = $posts_service;

        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // 認証ユーザIDを取得
        $user = Auth::user();
        // 一覧を取得
        $posts = $this->PostsService->getList($user->id);

        return view('Posts.index', compact('user', 'posts'));
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
