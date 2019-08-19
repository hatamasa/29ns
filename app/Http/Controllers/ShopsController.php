<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotFoundShopException;
use App\Repositories\PostsRepository;
use App\Services\ShopsService;
use App\Services\ApiService;

/**
 * 店舗コントローラ
 * @author hatamasa
 *
 */
class ShopsController extends Controller
{
    // 店舗表示件数
    const SHOPS_LIST_LIMIT = 30;
    // 店舗詳細での投稿の表示数
    const POSTS_LIST_LIMIT_4_SHOP_DETAIL = 10;

    public function __construct(ShopsService $ShopsService, ApiService $apiService, PostsRepository $posts)
    {
        parent::__construct();

        $this->ShopsService = $ShopsService;
        $this->ApiService   = $apiService;

        $this->Posts = $posts;

        $this->middleware('verified')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        // リクエストパラメータからオプションを生成
        $input = $request->input();
        $input['page'] = $input['page'] ?? 1;
        try {
            list($options, $search_condition) = $this->ShopsService->makeOptions($input, self::SHOPS_LIST_LIMIT);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->_log($e->getMessage(), 'error');
            return redirect(url()->previous());
        }

        try {
            // 店舗を取得
            $shops = $this->ApiService->callGnaviRestSearchApi($options, ApiService::CATEGORY_YAKINIKU);
        } catch (NotFoundShopException $e){
            session()->flash('error', '検索結果がありません');
            return redirect(url()->previous());
        } catch (\Exception $e) {
            session()->flash('error', '予期せぬエラーが発生しました。');
            $this->_log($e->getMessage(), 'error');
            return redirect(url()->previous());
        }
        $total_hit_count = $shops['total_hit_count'];

        $shops = $this->ShopsService->margeAttr($shops['rest']);

        $shops = new LengthAwarePaginator(
                $shops,
                $total_hit_count,
                self::SHOPS_LIST_LIMIT,
                $request->page,
                ['path' => $request->url()]
            );

        return view('shops.index', compact("input", "total_hit_count", "shops", "search_condition"));
    }

    public function ranking(Request $request)
    {
        $page = $request->page ?? 1;
        $shops = $this->ShopsService->getList4PopularityList(self::SHOPS_LIST_LIMIT, $page);
        $count = DB::table('shops')->count();

        $shops = new LengthAwarePaginator(
                $shops,
                $count > 100 ? 100 : $count,
                self::SHOPS_LIST_LIMIT,
                $page,
                ['path' => $request->url()]
            );

        $offset = ($page - 1) * self::SHOPS_LIST_LIMIT;

        return view('shops.ranking', compact('shops', 'offset'));
    }

    public function show(Request $request, $shop_cd)
    {
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

        $page = $request->page ?? 1;
        $count = DB::table('posts')->where('shop_cd', $shop_cd)->count();
        $posts = $this->Posts->getListByShopCd($shop_cd, self::POSTS_LIST_LIMIT_4_SHOP_DETAIL, $page);

        $posts = new LengthAwarePaginator(
                $posts,
                $count,
                self::POSTS_LIST_LIMIT_4_SHOP_DETAIL,
                $page,
                ['path' => $request->url()]
            );
        $redirect_url = url()->previous();

        return view('shops.show', compact('shop', 'posts', 'redirect_url'));
    }

    public function like()
    {
    }

}
