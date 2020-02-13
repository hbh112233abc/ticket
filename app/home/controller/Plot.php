<?php

declare(strict_types=1);

namespace app\home\controller;

use think\facade\Request;
use think\facade\View;
use think\facade\Db;

class Plot extends Wx
{
    protected $middleware = [\app\home\middleware\WxAuth::class];
    protected $user;

    protected function initialize()
    {
        parent::initialize();

        // 已经登录过
        $this->user = session('wechat_user') ?? [];
    }
    /**
     * 小区首页
     *
     * @return \think\Response
     */
    public function index()
    {
        $people = Db::name('people')->where('openid', $this->user['id'])->find();
        $plot = [];
        if (!empty($people)) {
            $plot = Db::name('plot')->where('id', $people['plot_id'])->find();
        }
        View::assign('user', $this->user);
        View::assign('plot', $plot);
        View::assign('people', $plot);
        return View::fetch();
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
        View::assign('user', $this->user);
        return View::fetch();
    }

    /**
     * 编辑小区
     *
     * @return \think\Response
     */
    public function edit()
    {
        $plotId = Request::param('id', 0);
        if (empty($plotId)) {
            return '请选择小区';
        }
        $plot = Db::name('plot')->where('id', $plotId)->find();
        if (empty($plot)) {
            return '未找到小区';
        }
        View::assign('plot', $plot);
        return View::fetch('create');
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
