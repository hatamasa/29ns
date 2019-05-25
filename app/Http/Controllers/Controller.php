<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
        $this->middleware('before');
    }

    // 汎用ログ
    public function _log($msg, $level = "debug")
    {
        $debug = debug_backtrace();
        $message = $debug[1]["class"]."::".$debug[1]["function"]."(".$debug[0]["line"].") ".$msg;
        Log::log($level, $message);

        return true;
    }

}
