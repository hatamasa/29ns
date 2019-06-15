<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'sex'      => ['required', 'integer', 'digits_between:1,2'],
            'birth_y'  => ['required', 'integer', 'between:1900,2019'],
            'birth_m'  => ['required', 'integer', 'between:1,12'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'name.required'      => '表示ユーザ名を入力してください',
            'name.max'           => '表示ユーザ名は255文字以内で入力してください',
            'email.required'     => 'メールアドレスを入力してください',
            'email.email'        => 'メールアドレスが不正な形式です',
            'email.max'          => 'メールアドレスは255文字以内で入力してください',
            'email.unique'       => 'すでに登録されているメールアドレスです',
            'sex.required'       => '性別を入力してください',
            'birth_y.required'   => '誕生年を入力してください',
            'birth_y.between'    => '誕生年は1900~2019の間で入力してください',
            'birth_m.required'   => '誕生月を入力してください',
            'birth_m.between'    => '誕生月は1~12で入力してください',
            'password.requied'   => 'パスワードを入力してください',
            'password.min'       => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワード(確認)は同じパスワードを入力してください',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'sex'      => $data['sex'],
            'birth_ym' => intval((string)$data['birth_y'].sprintf('%02d', $data['birth_m'])),
            'password' => Hash::make($data['password']),
        ]);
    }
}
