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

    public function store(Request $request, $id)
    {
        $contents = $request->contents;

        try {
            DB::table('post_comments')->insert([
                'post_id'  => $id,
                'user_id'  => Auth::id(),
                'contents' => $contents,
            ]);
        } catch (\Exception $e) {
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', 'コメントしました');
        return redirect(url()->previous());
    }

    public function show(Request $request, $id)
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy(Request $request, $id)
    {
        try {
            DB::table('post_comments')->delete($id);
        } catch (\Exception $e) {
            $this->_log($e->getMessage(), 'error');
            session()->flash('error', '予期せぬエラーが発生しました。');
            return redirect(url()->previous());
        }

        session()->flash('success', '削除しました');
        return redirect(url()->previous());
    }

}