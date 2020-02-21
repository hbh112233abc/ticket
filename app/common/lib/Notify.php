<?php

namespace app\common\lib;

use think\facade\Log;
use think\facade\Db;

class Notify extends Wx
{
    public function newInto($people)
    {
        $adminOpenidList = Db::name('people')->where('group_id', 1)->where('state', 1)->where('plot_id', $people['plot_id'])->column('openid');
        if (empty($adminOpenidList)) {
            Log::error('小区' . $people['plot_id'] . '没有管理员');
            return;
        }
        $plotName = Db::name('plot')->where('id', $people['plot_id'])->value('title');
        $msgTplId = config('wx.notify.new_into');
        $this->wx->template_message->send([
            'touser' => $adminOpenidList,
            'template_id' => $msgTplId,
            'url' => url('home/plot/check', ['people_id' => $people['id']], false, true)->build(),
            'data' => [
                'plot' => $plotName,
                'username' => $people['username'],
                'time' => date('Y年m月d日 H:i:s'),
            ],
        ]);
    }
}
