<?php

declare(strict_types=1);

namespace app\home\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Qrcode extends Model
{
    const TYPE_ADMIN = 0;
    const TYPE_SERVER = 1;
    const TYPE_OWNER = 2;
    const TYPE_GUEST = 3;

    const STATE_NOT_USED = 0;
    const STATE_USED = 1;
    const STATE_INVALID = -1;
    /**
     * 关联人员表
     *
     * @return void
     */
    public function people()
    {
        return $this->belongsTo(People::class, 'user_id', 'id');
    }


    public static function getType($groupId)
    {
        if (in_array($groupId, Group::ADMIN_ID)) {
            return self::TYPE_ADMIN;
        }
        if (in_array($groupId, Group::SERVER_ID)) {
            return self::TYPE_SERVER;
        }
        if (in_array($groupId, Group::OWNER_ID)) {
            return self::TYPE_OWNER;
        }
        return self::TYPE_GUEST;
    }

    /**
     * 检查验证权限
     *
     * @param [type] $people
     * @param [type] $qr
     * @return boolean
     */
    public static function hasCheckAuth($people, $qr)
    {
        if (!in_array($people['group_id'], Group::CHECK_ID)) {
            return false;
        }
        if ($people['plot_id'] != $qr['plot_id']) {
            return false;
        }
        return true;
    }

    protected function getTypeTextAttr($value, $data)
    {
        if ($data['type'] == self::TYPE_GUEST) {
            return '访客';
        }
        return Group::title($data['people']['group_id']);
    }

    /**
     * json_decode获取content内容
     *
     * @param [type] $value
     * @param [type] $data
     * @return void
     */
    protected function getContentAttr($value, $data)
    {
        if (empty($value)) {
            return [];
        }
        return json_decode($value, true);
    }

    /**
     * 生成二维码
     *
     * @param People $people
     * @param boolean $isGuest
     * @param array $remark
     * @return void
     */
    public static function makeQr(People $people, $isGuest = false, $remark = [])
    {
        $type = self::getType($people['group_id']);
        if ($isGuest) {
            $type = self::TYPE_GUEST;
        }

        $where = [
            ['plot_id', '=', $people['plot_id']],
            ['type', '=', $type],
            ['user_id', '=', $people['id']],
            ['state', '=', 0],
        ];
        $qr = self::where($where)->findOrEmpty();
        if (!$qr->isEmpty()) {
            return $qr;
        }

        $qr = self::create([
            'type' => $type,
            'content' => json_encode($remark),
            'plot_id' => $people['plot_id'],
            'room' => $people['room'],
            'user_id' => $people['id'],
            'state' => 0,
            'create_time' => date('Y-m-d H:i:s'),
            'expire_time' => self::expireTime($type),
        ]);
        return $qr;
    }

    /**
     * 扫码验证数据
     */
    public static function check($data)
    {
        $qr = self::where('id', $data['qr_id'])->findOrEmpty();
        if ($qr->isEmpty()) {
            return '通行码错误';
        }
        if ($qr->state == self::STATE_INVALID) {
            return '通行码失效';
        }
        $qr->state = $data['state'];
        $qr->check_time = date('Y-m-d H:i:s');
        $qr->save();
        $data['remark'] = json_encode($data);
        Record::create($data);
        return true;
    }

    /**
     * 过期时间
     *
     * @param integer $type
     * @return void
     */
    public static function expireTime($type = 0)
    {
        switch ($type) {
            case self::TYPE_ADMIN:
                $time = date('Y-m-d H:i:s', strtotime('+1 year'));
                break;
            case self::TYPE_GUEST:
                $time = date('Y-m-d H:i:s', strtotime('+12 hour'));
                break;
            default:
                $time = date('Y-m-d H:i:s', strtotime('+1 month'));
                break;
        }
        return $time;
    }
}
