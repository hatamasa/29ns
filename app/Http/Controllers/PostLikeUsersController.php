<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostLikeUsersController extends Controller
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
        $request->validate([
            'post_id' => 'required|integer'
        ]);

        $input = $request->input();

        DB::beginTransaction();
        try {
            DB::table('post_like_users')->insert([
                'post_id' => $input['post_id'],
                'user_id' => Auth::id()
            ]);
            $post = DB::table('posts')->where(['id' => $input['post_id']])->first();
            $like_count = $post->like_count+1;
            DB::table('posts')->where(['id' => $input['post_id']])->update(['like_count' => $like_count]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage());
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        $result['like_count'] = $like_count;
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
        DB::beginTransaction();
        try {
            DB::table('post_like_users')->where([
                'post_id' => $id,
                'user_id' => Auth::id()
            ])->delete();
            $post = DB::table('posts')->where(['id' => $id])->first();
            $like_count = $post->like_count-1;
            DB::table('posts')->where(['id' => $id])->update(['like_count' => $like_count]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage());
            $result['return_code'] = 0;
            $result['message'] = '予期せぬエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $result['return_code'] = 1;
        $result['like_count'] = $like_count;
        return response()->json($result, Response::HTTP_OK);
    }

}
