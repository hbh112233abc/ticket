<?php

namespace app\home\controller;

use think\facade\Request;

class Base
{
    // protected $middleware = [\app\home\middleware\WxAuth::class];
    protected $user;

    function __construct()
    {
        // 已经登录过
        $this->user = session('wechat_user') ?? ['name' => 'hbh', 'id' => 'o2VKivzAd8i51MDbVICYAYJMbGTQ'];
        \think\facade\View::assign('user', $this->user);
    }

    protected function success($msg = 'success', $data = [], $url = '', $wait = 2)
    {
        if (Request::isAjax()) {
            return json(['code' => 1, 'msg' => $msg, 'data' => $data, 'url' => $url]);
        }
        return view('public/jump', ['code' => 1, 'msg' => $msg, 'data' => $data, 'url' => $url, 'wait' => $wait]);
    }

    protected function error($msg, $url = '', $code = 0, $wait = 2)
    {
        if (Request::isAjax()) {
            return json(['code' => $code, 'msg' => $msg, 'url' => $url]);
        }
        return view('public/jump', ['code' => $code, 'msg' => $msg, 'url' => $url, 'wait' => $wait]);
    }
}
