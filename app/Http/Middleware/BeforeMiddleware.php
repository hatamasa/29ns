<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Closure;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        View::share('user', Auth::user());

        $current = url()->current();
        $previous = explode("?", url()->previous())[0];
        // 別の画面に遷移した場合リファラーの処理
        if ($request->isMethod('GET') && $current != $previous
            && parse_url($current, PHP_URL_HOST) === parse_url($previous, PHP_URL_HOST)) {
            $referrers = session('referrers') ?? [];
            $length = count($referrers);
            $end = explode('?', end($referrers))[0];
            if ($end == $current) {
                // リファラー履歴の最後と今のURLが一致した場合は履歴から削除する
                unset($referrers[$length-1]);
            }
            else if ($end == $previous) {
                // リファラー履歴の最後とリファラが一致した場合は何もしない
            }
            else if ($length < 2
                || ($length >= 2 && explode('?', $referrers[$length-2])[0] != $current)) {
                // 2つ前と違う画面の場合はリファラを追加する
                $referrers[] = url()->previous();
            }
            session(['referrers' => $referrers]);
        }

        return $next($request);
    }
}
