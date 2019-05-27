<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $options = $this->ShopsService->makeOptions($request->input(), self::SHOPS_LIST_LIMIT);
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
        $shops = $this->ShopsService->margeScore($shops);

        $shops = $shops['rest'];

        return view('Shops.index', compact("shops"));
    }

    public function show()
    {
    }

    public function like()
    {
    }

}
