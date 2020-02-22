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
    const SERVER_ID = [2, 3, 5]; //服务人员
    const OWNER_ID = [4]; //业主
    const CHECK_ID = [1, 2, 3]; //验证角色

    public static function title($groupId)
    {
        $titleArr = self::where('state', 1)->cache(600)->column('title', 'id');
        if (empty($titleArr[$groupId])) {
            return '访客';
        }
        return $titleArr[$groupId];
    }
}
