<?php

declare(strict_types=1);

namespace app\home\model;

use think\Model;

/**
 * @mixin think\Model
 */
class People extends Model
{
    static public function isAdmin($user, $plotId)
    {
        $where = [
            ['state', '=', 1],
            ['plot_id', '=', $plotId],
            ['openid', '=', $user['id']],
        ];
        $groupId = self::where($where)->value('group_id');
        if (empty($groupId)) {
            return false;
        }
        if (in_array($groupId, Group::ADMIN_ID)) {
            return true;
        }
        return false;
    }

    static public function check($plotId, $peopleIds, $state = 1)
    {
        $res = self::where('plot_id', $plotId)->where('id', 'in', $peopleIds)->update(['state' => $state]);
        return $res;
    }

    /**
     * 关联角色
     *
     * @return void
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * 关联小区
     *
     * @return void
     */
    public function plot()
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'id');
    }
}
