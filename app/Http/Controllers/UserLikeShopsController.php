<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $shop_cd = $request->input('shop_cd');
        // Apiで店舗を取得
        $shop = $this->ShopsService->getShopByCd($shop_cd);
        if (! count($shop)) {
            $this->_log('users not found. shop_cd='.$shop_cd);
            session()->flash('error', '存在しない店舗です。');
            return redirect(url()->previous());
        }

        DB::beginTransaction();
        try {
            DB::table('user_like_shops')->insert([
                'user_id' => Auth::id(),
                'shop_cd' => $shop_cd
            ]);
            DB::table('shops')->where(['shop_cd' => $shop_cd])->update(['like_count' => $shop['like_count']+1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', $shop['name'].'をお気に入りしました。');
        return redirect(url()->previous());
    }

    public function destory(Request $request, $shop_cd)
    {
        // Apiで店舗を取得
        $shop = $this->ShopsService->getShopByCd($shop_cd);
        if (! count($shop)) {
            $this->_log('users not found. shop_cd='.$shop_cd);
            session()->flash('error', '存在しない店舗です。');
            return redirect(url()->previous());
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
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('info', $shop['name'].'をお気に入り解除しました。');
        return redirect(url()->previous());
    }


}