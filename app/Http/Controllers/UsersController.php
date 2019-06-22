<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ImgUploader;

class UsersController extends Controller
{

    public function __construct(UsersRepository $users, UsersService $usersService, ImgUploader $imgUploader)
    {
        parent::__construct();

        $this->Users = $users;
        $this->UsersService = $usersService;
        $this->ImgUploader = $imgUploader;

        $this->middleware('verified');
    }

    public function show(Request $request, $id)
    {
        $input = $request->input();
        $params = $request->all();
        $tab = $params['tab'] ?? 1;

        $count = DB::table('users')->where(['id' => $id, 'is_resigned' => 0])->count();
        if (! $count) {
            $this->_log('invalid user_id. user_id='.$id);
            session()->flash('error', '退会済みのユーザです。');
            return redirect(url()->previous());
        }
        $users = $this->Users->getUserDetail($id);
        // タブに表示するリストを取得する
        $list = $this->UsersService->getList4TabArea($request, $users->id, $tab);

        return view('users.show', compact('users', 'list', 'tab', 'input'));
    }

    public function edit(Request $request, $id)
    {
        $users = DB::table('users')->where(['id' => $id])->first();
        if (! $users) {
            $this->_log('invalid user_id. user_id='.$id);
            session()->flash('error', '存在しないユーザです。');
            return redirect(url()->previous());
        }

        return view('users.edit', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contents' => 'required|string|max:200',
            'file' => 'nullable|file|image|mimes:jpeg,png',
        ]);

        $input = $request->input();
        DB::beginTransaction();
        try {
            if (! empty($request->file('file'))) {
                // s3にアップロード
                $img_path = $this->ImgUploader->uploadUserImg($request);
            }
            DB::table('users')
                ->where(['id' => $input['user_id']])
                ->update([
                    'thumbnail_url' => $img_path,
                    'contents'      => $input['contents']
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '予期せぬエラーが発生しました。');
            $this->_log($e->getMessage(), 'error');
            return redirect(url()->previous())->with($request->input());
        }

        session()->flash('success', '保存しました。');
        return redirect(url()->previous())->with($request->input());
    }

}