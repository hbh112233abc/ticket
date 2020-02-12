<?php

namespace app\home\controller;

use EasyWeChat\Factory;

class Wx extends \app\BaseController
{
    protected $wx;

    protected function initialize()
    {
        $config = config('wx');
        $this->wx = Factory::officialAccount($config);
    }

    public function index()
    {
        $response = $this->wx->server->serve();

        // 将响应输出
        return $response->send();
    }
}
