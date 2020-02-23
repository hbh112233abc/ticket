<?php

declare(strict_types=1);

namespace app\home\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Record extends Model
{
    protected function getRemarkAttr($value, $data)
    {
        return json_decode($value, true);
    }
}
