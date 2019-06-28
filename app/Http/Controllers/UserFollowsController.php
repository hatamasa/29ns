<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $result = [];
        if (! $request->ajax()) {
            $this->_log('method not ajax.');
            $result['return_code'] = 0;
            $result['message'] = '不正なアクセスです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $follow_user_id = $request->input('follow_user_id');
        $users = DB::table('users')->where([
                'id' => $follow_user_id,
                'is_resigned' => 0
            ])->first();

        if (!$users) {
            $this->_log('users not found. user_id='.$follow_user_id);
            $result['return_code'] = 0;
            $result['message'] = '存在しないユーザです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        return response()->json($result, Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $result = [];
        if (! $request->ajax()) {
            $this->_log('method not ajax.');
            $result['return_code'] = 0;
            $result['message'] = '不正なアクセスです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $users = DB::table('users')->where([
                'id' => $id,
                'is_resigned' => 0
            ])->first();

        if (!$users) {
            $this->_log('users not found. user_id='.$id);
            $result['return_code'] = 0;
            $result['message'] = '存在しないユーザです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        return response()->json($result, Response::HTTP_OK);
    }


}