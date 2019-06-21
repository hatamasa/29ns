<?php

namespace App\Http\Controllers;

use App\Services\ImgUploader;
use App\Services\PostsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\FileUploadException;
use App\Exceptions\NotFoundShopException;
use App\Repositories\PostCommentsRepository;
use App\Services\ShopsService;

class PostsController extends Controller
{
    // 29ログ一覧表示件数
    const POSTS_LIST_LIMIT = 30;

    public function __construct(
        PostsService $postsService,
        ShopsService $shopsService,
        PostCommentsRepository $postComments,
        ImgUploader $imgUploader)
    {
        parent::__construct();
        $this->PostsService = $postsService;
        $this->ShopsService = $shopsService;

        $this->PostComments = $postComments;
        $this->ImgUploader  = $imgUploader;

        $this->middleware('verified')->except(['index']);
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

    public function create(Request $request)
    {
        $shop_cd = $request->shop_cd;

        try {
            // 店舗を取得
            $shop = $this->ShopsService->getShopByCd($shop_cd);
        } catch (NotFoundShopException $e){
            session()->flash('error', '検索結果がありません');
            return redirect(url()->previous());
        } catch (\Exception $e) {
            session()->flash('error', '予期せぬエラーが発生しました。');
            $this->_log($e->getMessage(), 'error');
            return redirect(url()->previous());
        }

        return view('Posts.create', compact("shop"));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $result = $this->PostsService->validateStore($params);
        if (! $result) {
            session()->flash('error', "予期せぬエラーが発生しました");
            $this->_log("invalid parameter. ".json_encode($params), "error");
            return redirect(url()->previous());
        }

        DB::beginTransaction();
        try {
            if (! empty($request->file('files'))) {
                // s3にアップロード
                $img_paths = $this->ImgUploader->uploadPostsImg($request);
            }

            DB::table("posts")->insert([
                "user_id"       => Auth::id(),
                "shop_cd"       => $params["shop_cd"],
                "score"         => $params["score"],
                "visit_count"   => $params["visit_count"],
                "title"         => $params["title"],
                "contents"      => $params["contents"],
                "img_url_1"     => $img_paths[0] ?? null,
                "img_url_2"     => $img_paths[1] ?? null,
                "img_url_3"     => $img_paths[2] ?? null,
                'like_count'    => 0,
                'comment_count' => 0,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '予期せぬエラーが発生しました。');
            $this->_log($e->getMessage(), 'error');
            return redirect(url()->previous())->with($request->input());
        }

        session()->flash('success', '投稿しました');
        return redirect(url("/shops/{$params['shop_cd']}"));
    }

    public function show(Request $request, $id)
    {
        $post = $this->PostsService->getPostById($id);
        $post_comments = $this->PostComments->getListByPostId($id);

        return view('Posts.show', compact('post', 'post_comments'));
    }

    // TODO: 表示する導線を作成
    public function showLikeUsers(Request $request, $id)
    {
        $post_like_users = DB::table('post_like_users')->where('post_id', $id)->get();

        return view('Posts.like_users', compact('post_like_users'));
    }

    public function destroy()
    {
        // TODO: 実装
    }

}
