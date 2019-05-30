<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        list($options, $search_condition) = $this->ShopsService->makeOptions($input, self::SHOPS_LIST_LIMIT + 1);
        if (!is_array($options)) {
            session()->flash('error', $options);
            return redirect(url()->previous());
        }

        try {
            // 店舗を取得
            $shops = $this->ApiService->callGnaviRestSearchApi($options, ApiService::CATEGORY_YAKINIKU);
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

    public function show()
    {
    }

    public function like()
    {
    }

}
