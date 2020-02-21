<?php

declare(strict_types=1);

namespace app\home\controller;

use think\facade\Request;
use think\facade\View;
use think\facade\Db;

use app\home\validate\Plot as PlotValidate;
use think\exception\ValidateException;

use app\common\lib\Notify;

class Plot
{
    protected $middleware = [\app\home\middleware\WxAuth::class];
    protected $user;

    function __construct()
    {
        // 已经登录过
        $this->user = session('wechat_user') ?? ['name' => 'hbh', 'id' => 11];
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
     * 查看小区
     *
     * @return void
     */
    public function view()
    {
        $id = Request::param('id', 0);
        $plot = Db::name('plot')->where('id', $id)->find();
        if (empty($plot)) {
            return redirect(url('home/plot/index')->build());
        }
        View::assign('plot', $plot);
        $people = Db::name('people')->where('openid', $this->user['id'])->find();
        // $people = [];
        View::assign('people', $people);
        return View::fetch();
    }

    /**
     * 入驻小区
     *
     * @return void
     */
    public function into()
    {
        if (Request::isAjax()) {
            $data = Request::post();
            try {
                validate(PlotValidate::class)->scene('into')->check($data);
            } catch (ValidateException $e) {
                return json(['code' => 0, 'msg' => $e->getError()]);
            }
            $groupArr =  Db::name('group')->where('state', 1)->cache(1800)->column('id', 'title');
            if (empty($groupArr[$data['group_name']])) {
                return json(['code' => 0, 'msg' => '角色不存在']);
            }
            $data['group_id'] = $groupArr[$data['group_name']];
            if (empty($data['id'])) {
                $res = Db::name('people')->strict(false)->insertGetId($data);
                $data['id'] = $res;
            } else {
                $res = Db::name('people')->strict(false)->save($data);
            }
            if (empty($res)) {
                return json(['code' => 0, 'msg' => '操作失败']);
            }
            //通知管理员
            $notify = new Notify;
            $notify->newInto($data);
        }
        $plotId = Request::param('id/d', 0);
        $plot = Db::name('plot')->where('id', $plotId)->find();
        if (empty($plot)) {
            return redirect(url('home/plot/search'));
        }
        View::assign('plot', $plot);
        $people = Db::name('people')->where('openid', $this->user['id'])->find();
        View::assign('people', $people);
        View::assign('user', $this->user);
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
        $people = Db::name('people')->where('openid', $this->user['id'])->find();
        if (Request::isAjax()) {
            if (!empty($people['plot_id'])) {
                return json(['code' => 1, 'url' => url('home/plot/view', ['id' => $people['plot_id']])->build()]);
            }

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
            return json(['code' => 1, 'url' => url('home/plot/view', ['id' => $plotId])->build()]);
        }
        if (!empty($people)) {
            if (!empty($people['plot_id'])) {
                if ($people['group_id'] == 1) {
                    //物业管理员
                    return redirect(url('home/plot/edit', ['id' => $people['plot_id']])->build());
                }
                return redirect(url('home/plot/view', ['id' => $people['plot_id']])->build());
            }
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
