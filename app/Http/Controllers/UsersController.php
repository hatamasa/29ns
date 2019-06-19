<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function __construct(UsersRepository $users, UsersService $usersService)
    {
        parent::__construct();

        $this->Users = $users;
        $this->UsersService = $usersService;
    }

    public function show(Request $request, $id)
    {
        $input = $request->input();
        $params = $request->all();
        $tab = $params['tab'] ?? 1;

        $users = $this->Users->getUserDetail($id);

        if (! $users) {
            $this->_log('invalid user_id. user_id='.$id);
            session()->flash('error', 'ユーザがすでに退会しています。');
            return redirect(url()->previous());
        }
        // タブに表示するリストを取得する
        $list = $this->UsersService->getList4TabArea($request, $users->id, $tab);

        return view('users.show', compact('users', 'list', 'tab', 'input'));
    }

    public function edit(Request $request, $id)
    {

    }

    public function destory(Request $request)
    {

    }

}