<?php

declare(strict_types=1);

namespace app\home\controller;

use app\home\model\People as PeopleModel;
use app\home\model\Qrcode;
use app\home\model\Record;
use app\home\model\Group;
use think\facade\Request;
use think\facade\View;

class Tick extends Base
{
    protected $people;
    function __construct()
    {
        parent::__construct();
        $this->people = PeopleModel::where('openid', $this->user['id'])->findOrEmpty();
        if ($this->people->isEmpty()) {
            return $this->error('您未入驻小区', url('home/plot/index'));
        }
        View::assign('people', $this->people);
    }
    /**
     * 通行证首页
     *
     * @return void
     */
    public function index()
    {
        $qr = Qrcode::makeQr($this->people);
        return view('', ['qr' => $qr]);
    }

    /**
     * 访客通行证.
     *
     * @return \think\Response
     */
    public function guest()
    {
        if (Request::isAjax()) {
            $content = input('post.content', []);
            if (empty($content['reason'])) {
                return $this->error('请选择到访原因');
            }
            if (empty($content['number'])) {
                return $this->error('请填写访客数量');
            }
            if ($content['number'] < 1) {
                return $this->error('访客数量错误');
            }
            $qrId = input('post.id/d', 0);
            $qr = Qrcode::where('id', $qrId)->findOrEmpty();
            if ($qr->isEmpty()) {
                $qr = Qrcode::makeQr($this->people, true, $content);
            } else {
                if ($qr['user_id'] != $this->people['id']) {
                    return $this->error('无修改权限');
                }
                $qr['content'] = json_encode($content);
                $qr->save();
            }
            return $this->success('生成访客二维码', [], url('home/tick/guest', ['qr_id' => $qr['id']])->build());
        }
        $qrId = input('qr_id/d', 0);
        if ($qrId) {
            $qr = Qrcode::where('id', $qrId)->findOrEmpty();
            if ($qr->isEmpty()) {
                return $this->error('访客二维码不存在', url('home/plot/index')->build());
            }
        } else {
            $qr = Qrcode::where('user_id', $this->people['id'])->where('type', Qrcode::TYPE_GUEST)->findOrEmpty();
        }
        return view('', ['qr' => $qr]);
    }

    /**
     * 通行记录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function record()
    {
        //
    }

    /**
     * 扫码界面
     *
     * @return void
     */
    public function scan()
    {
        if (!in_array($this->people['group_id'], Group::CHECK_ID)) {
            return $this->error('无验证权限', url('home/plot/index')->build());
        }

        return view();
    }

    /**
     * 通行证认证
     *
     * @return \think\Response
     */
    public function check()
    {
        if (Request::isAjax()) {
            //提交验证
            $data = input('post.');
            $data['create_time'] = date('Y-m-d H:i:s');
            if (empty($data['qr_id'])) {
                //仅记录通行记录
                $data['remark'] = json_encode($data);
                $data['qr_id'] = 0;
                $data['user_id'] = 0;
                $res = Record::create($data);
                return $this->success('操作成功', [], url('home/tick/scan')->build());
            }

            $res = Qrcode::check($data);
            if ($res !== true) {
                return $this->error($res);
            }
            return $this->success('操作成功', [], url('home/tick/scan')->build());
        }
        $qrId = input('qr_id/d', 0);
        $qr = Qrcode::where('id', $qrId)->findOrEmpty();
        if ($qr->isEmpty()) {
            return $this->error('通行证无效', url('home/plot/index')->build());
        }
        if (!Qrcode::hasCheckAuth($this->people, $qr)) {
            return $this->error('您没有验证权限', url('home/plot/index')->build());
        }

        return view('', ['qr' => $qr]);
    }
}
