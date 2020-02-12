<?php

namespace app\home\controller;

use EasyWeChat\Factory;

class Wx extends \app\BaseController
{
    protected $app;

    function __construct()
    {
        parent::__construct();
        $config = config('wx');
        $this->app = Factory::officialAccount($config);
    }

    public function index()
    {
        $response = $app->server->serve();

        // 将响应输出
        return $response->send();
    }
}
