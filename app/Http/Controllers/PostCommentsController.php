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
        $post_id  = $request->post_id;
        $contents = $request->contents;

        try {
            DB::table('post_comments')->insert([
                'post_id'  => $post_id,
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

    public function destroy()
    {
    }

}
