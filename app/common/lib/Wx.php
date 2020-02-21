<?php

namespace app\common\lib;

use EasyWeChat\Factory;

class Wx
{
    protected $wx;

    function __construct()
    {
        $config = config('wx');
        $this->wx = Factory::officialAccount($config);
    }
}
