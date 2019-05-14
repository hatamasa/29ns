<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン直後の処理
     * @param \Illuminate\Http\Request $request
     * @param unknown $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // HTTP設定
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => implode(PHP_EOL, [
                    'Content-Type: application/json',
                ]),
                'content' => json_encode([
                    'email'    => $request->email,
                    'password' => $request->password,
                ]),
            ],
        ];
        $contents = file_get_contents(url('/api/v1/login'), false, stream_context_create($options));

        // レスポンスステータス
        $statusCode = http_response_code();
        if($statusCode === 200) {
            // 200 success
            // トークンを更新
            $result = json_decode($contents, true);
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'access_token' => $result['access_token'],
                ]);
        } elseif(preg_match ("/^4\d\d/", $statusCode)) {
            // 4xx Client Error
            Log::error("token get error. statusCode=".$statusCode);
        } elseif(preg_match ('/^5\d\d/', $statusCode)) {
            // 5xx Server Error
            Log::error("token get error. statusCode=".$statusCode);
        } else {
            Log::error("token get error. statusCode=".$statusCode);
        }

        // ログイン後のリダイレクト
        return redirect()->intended($this->redirectPath());
    }

}
