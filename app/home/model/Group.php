<?php

declare(strict_types=1);

namespace app\home\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Group extends Model
{
    const ADMIN_ID = [1]; //管理员角色id数组
}
