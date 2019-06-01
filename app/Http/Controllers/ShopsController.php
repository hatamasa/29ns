<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotFoundShopException;
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

    public function __construct(ShopsService $ShopsService, ApiService $apiService)
    {
        parent::__construct();

        $this->ShopsService = $ShopsService;
        $this->ApiService   = $apiService;

        $this->middleware('auth')->except(['index']);
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

        $shops = $this->ShopsService->margeScore($shops['rest']);

        $shops = new LengthAwarePaginator(
                $shops,
                $total_hit_count,
                self::SHOPS_LIST_LIMIT,
                $request->page,
                ['path' => $request->url()]
            );

        return view('Shops.index', compact("input", "total_hit_count", "shops", "search_condition"));
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
                $request->page,
                ['path' => $request->url()]
            );

        $offset = ($page - 1) * self::SHOPS_LIST_LIMIT;

        return view('Shops.ranking', compact('shops', 'offset'));
    }

    public function show()
    {
    }

    public function like()
    {
    }

}
