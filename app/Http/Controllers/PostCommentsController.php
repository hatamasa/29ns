<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostCommentsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $contents = $request->contents;
        if (empty(trim($contents))) {
            session()->flash('error', 'コメントを入力してください。');
            return redirect(url()->previous());
        }
        $post_id = $request->post_id;

        $Posts = DB::table('posts');
        $post = $Posts->where(['id' => $post_id])->first();

        DB::beginTransaction();
        try {
            DB::table('post_comments')->insert([
                'post_id'  => $post_id,
                'user_id'  => Auth::id(),
                'contents' => $contents,
            ]);
            $Posts->where('id', $post_id)->update(['comment_count' => $post->comment_count+1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', 'コメントしました');
        return redirect(url()->previous());
    }

    public function destroy(Request $request, $id)
    {
        $PostComments = DB::table('post_comments');
        $post_comment = $PostComments->where(['id' => $id])->first();

        $Posts = DB::table('posts');
        $post = $Posts->where(['id' => $post_comment->post_id])->first();

        DB::beginTransaction();
        try {
            $PostComments->delete($id);
            $Posts->where(['id' => $post->id])->update(['comment_count' => $post->comment_count-1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', '削除しました');
        return redirect(url()->previous());
    }

}
