<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserFollowsController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('verified');
    }

    public function store(Request $request)
    {
        $follow_user_id = $request->input('follow_user_id');
        $users = DB::table('users')->where(['id' => $follow_user_id])->first();
        if (!$users) {
            $this->_log('users not found. user_id='.$follow_user_id);
            session()->flash('error', '存在しないユーザです。');
            return redirect(url()->previous());
        }

        DB::beginTransaction();
        try {
            DB::table('user_follows')->insert([
                'user_id'        => Auth::id(),
                'follow_user_id' => $follow_user_id
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', $users->name.'さんをフォローしました。');
        return redirect(url()->previous());
    }

    public function destory(Request $request, $id)
    {
        $users = DB::table('users')->where(['id' => $id])->first();
        if (!$users) {
            $this->_log('users not found. user_id='.$id);
            session()->flash('error', '存在しないユーザです。');
            return redirect(url()->previous());
        }
        $name = $users->name;

        DB::beginTransaction();
        try {
            DB::table('user_follows')->where([
                'user_id'        => Auth::id(),
                'follow_user_id' => $id
            ])->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', $name.'さんをフォロー解除しました。');
        return redirect(url()->previous());
    }


}