<?php

declare(strict_types=1);

namespace app\home\controller;

use think\facade\Request;
use think\facade\View;

class Plot extends Wx
{
    protected $middleware = [app\home\middleware\WxAuth::class];
    protected $user;

    protected function initialize()
    {
        parent::initialize();

        // 已经登录过
        $this->user = session('wechat_user');
    }
    /**
     * 小区首页
     *
     * @return \think\Response
     */
    public function index()
    {
        dump($this->user);
    }

    /**
     * 小区二维码
     *
     * @return \think\Response
     */
    public function qr()
    {
    }

    /**
     * 创建小区
     *
     * @return \think\Response
     */
    public function create()
    {
    }

    /**
     * 编辑小区
     *
     * @return \think\Response
     */
    public function edit()
    {
    }

    /**
     * 系统设置
     *
     * @return \think\Response
     */
    public function setting()
    {
    }

    /**
     * 人员列表
     *
     * @return \think\Response
     */
    public function users()
    {
    }

    /**
     * 人员认证
     *
     * @return \think\Response
     */
    public function check()
    {
    }
}
