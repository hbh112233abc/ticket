<?php

namespace app\home\controller;

use think\facade\Request;

class Base
{
    // protected $middleware = [\app\home\middleware\WxAuth::class];

    protected function success($msg, $data, $url, $wait = 2)
    {
        if (Request::isAjax()) {
            return json(['code' => 1, 'msg' => $msg, 'data' => $data, 'url' => $url]);
        }
        return view('public/jump', ['code' => 1, 'msg' => $msg, 'data' => $data, 'url' => $url, 'wait' => $wait]);
    }

    protected function error($msg, $url, $code = 0, $wait = 2)
    {
        if (Request::isAjax()) {
            return json(['code' => $code, 'msg' => $msg, 'url' => $url]);
        }
        return view('public/jump', ['code' => $code, 'msg' => $msg, 'url' => $url, 'wait' => $wait]);
    }
}
