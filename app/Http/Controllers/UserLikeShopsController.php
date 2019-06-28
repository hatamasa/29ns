<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ShopsService;


class UserLikeShopsController extends Controller
{

    public function __construct(ShopsService $shopsService)
    {
        parent::__construct();

        $this->ShopsService = $shopsService;

        $this->middleware('verified');
    }

    public function store(Request $request)
    {
        $result = [];
        if (! $request->ajax()) {
            $this->_log('method not ajax.');
            $result['return_code'] = 0;
            $result['message'] = '不正なアクセスです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $shop_cd = $request->input('shop_cd');
        // Apiで店舗を取得
        $shop = $this->ShopsService->getShopByCd($shop_cd);
        if (! count($shop)) {
            $this->_log('users not found. shop_cd='.$shop_cd);
            $result['return_code'] = 1;
            $result['message'] = '存在しない店舗です。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            DB::table('user_like_shops')->insert([
                'user_id' => Auth::id(),
                'shop_cd' => $shop_cd
            ]);
            DB::table('shops')->where(['shop_cd' => $shop_cd])->update(['like_count' => ($shop['like_count'] ?? 0)+1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        return response()->json($result, Response::HTTP_OK);
    }

    public function destroy(Request $request, $shop_cd)
    {
        $result = [];
        if (! $request->ajax()) {
            $this->_log('method not ajax.');
            $result['return_code'] = 0;
            $result['message'] = '不正なアクセスです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Apiで店舗を取得
        $shop = $this->ShopsService->getShopByCd($shop_cd);
        if (! count($shop)) {
            $this->_log('users not found. shop_cd='.$shop_cd);
            $result['return_code'] = 1;
            $result['message'] = '存在しない店舗です。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            DB::table('user_like_shops')->where([
                'user_id' => Auth::id(),
                'shop_cd' => $shop_cd
            ])->delete();
            DB::table('shops')->where(['shop_cd' => $shop_cd])->update(['like_count' => $shop['like_count']-1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        return response()->json($result, Response::HTTP_OK);
    }

}