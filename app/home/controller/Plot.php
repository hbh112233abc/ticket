<?php

declare(strict_types=1);

namespace app\home\controller;

use think\Request;
use think\facade\View;

class Plot extends Wx
{
    protected $user;

    protected function initialize()
    {
        parent::initialize();
        $oauth = $this->wx->oauth;

        // 未登录
        if (empty($_SESSION['wechat_user'])) {

            $_SESSION['target_url'] = Request::url(true);

            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }

        // 已经登录过
        $this->user = $_SESSION['wechat_user'];
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
