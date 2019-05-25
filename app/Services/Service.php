<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class Service
{
    public function __construct()
    {
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