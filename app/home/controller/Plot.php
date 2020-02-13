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

    public function view()
    {
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
        if (Request::isAjax()) {
            $data = Request::post();
            $plotId = Db::name('plot')->strict(false)->insertGetId($data);
            $people = [
                'plot_id' => $plotId,
                'group_id' => 1,
                'room' => '0',
                'username' => $this->user['name'],
                'tel' => $data['tel'],
                'openid' => $this->user['id'],
            ];
            $res = Db::name('people')->strict(false)->insert($people);
            if (empty($res)) {
                return json(['code' => 0, 'message' => '创建失败']);
            }
            return json(['code' => 1, 'url' => url('home/plot/view', ['id' => $plotId])]);
        }
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
        if (Reqeust::isAjax()) {
            $data = Request::post();
            $res = Db::name('plot')->strict(false)->save($data);
            if (empty($res)) {
                return json(['code' => 0, 'message' => '修改失败']);
            }
            return json(['code' => 1, 'url' => url('home/plot/view', ['id' => $data['id']])]);
        }
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
