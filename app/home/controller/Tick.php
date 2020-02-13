<?php

declare(strict_types=1);

namespace app\home\controller;

use think\Request;

class Tick extends Wx
{
    protected $middleware = [\app\home\middleware\WxAuth::class];

    protected $user;

    protected function initialize()
    {
        parent::initialize();

        // 已经登录过
        $this->user = session('wechat_user');
    }

    /**
     * 我家通行证
     *
     * @return \think\Response
     */
    public function mine()
    {
        //
    }

    /**
     * 访客通行证.
     *
     * @return \think\Response
     */
    public function guest()
    {
        //
    }

    /**
     * 通行记录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function record(Request $request)
    {
        //
    }

    /**
     * 通行证认证
     *
     * @return \think\Response
     */
    public function check()
    {
        //
    }
}
