<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function update(Request $request)
    {
        $request->validate([
            'contents' => 'required|string|max:200',
            'tmp-path' => 'required|string'
        ]);

        $input = $request->input();
        DB::beginTransaction();
        try {
            if (! empty($input['tmp-path'])) {
                // s3にアップロード
                $img_path = $this->ImgUploader->uploadUserImg($input['tmp-path']);
            }
            $update = [];
            $update['contents'] = $input['contents'];
            if (isset($img_path)) {
                $update['thumbnail_url'] = $img_path;
            }
            DB::table('users')
                ->where(['id' => $input['user_id']])
                ->update($update);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '予期せぬエラーが発生しました。');
            $this->_log($e->getMessage(), 'error');
            return redirect(url("/users/{$input['user_id']}"));
        }

        session()->flash('success', '保存しました。');
        return redirect(url("/users/{$input['user_id']}"));
    }

    public function imageUpdate(Request $request)
    {
        $result = [];
        if (! $request->ajax()) {
            $this->_log('method not ajax.');
            $result['message'] = '不正なアクセスです。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // アップロードしたファイルのバリデーション設定
        $request->validate([
            'file' => 'required',
            'filename' => 'required|string'
        ]);

        $input = $request->input();
        try {
            // プロジェクト配下に配置
            $result['path'] = $this->ImgUploader->tmpUploadUserImg($input);
        } catch (\Exception $e) {
            $this->_log($e->getMessage(), 'error');
            $result['message'] = 'アップロードエラーが発生しました。';
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($result, Response::HTTP_OK);
    }

}