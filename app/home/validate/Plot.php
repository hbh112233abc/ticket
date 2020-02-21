<?php

declare(strict_types=1);

namespace app\home\validate;

use think\Validate;

class Plot extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'openid|微信id' => ['require'],
        'plot_id|小区id' => ['require'],
        'username|姓名' => ['require'],
        'group_name|角色' => ['require', 'in:物业管理员,物业保安,业主住户,保洁维修'],
        'tel|电话' => ['require', 'mobile'],
        'room|房间' => ['require'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'into' => ['openid', 'plot_id', 'username', 'tel', 'group_name', 'room'],
    ];
}
