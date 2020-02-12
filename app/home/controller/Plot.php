<?php

declare(strict_types=1);

namespace app\home\controller;

use think\Request;

class Plot extends Wx
{
    protected function initialize()
    {
        parent::initialize();
    }
    /**
     * 小区首页
     *
     * @return \think\Response
     */
    public function index()
    {
        //
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
