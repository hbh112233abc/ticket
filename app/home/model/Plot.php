<?php

declare(strict_types=1);

namespace app\home\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Plot extends Model
{
    /**
     * 获取入驻链接
     *
     * @param [type] $value
     * @param [type] $data
     * @return void
     */
    protected function getIntoUrlAttr($value, $data)
    {
        return url('home/plot/into', ['id' => $data['id']])->build();
    }

    /**
     * 关联人员
     *
     * @return void
     */
    public function people()
    {
        return $this->hasMany(People::class, 'plot_id', 'id');
    }

    /**
     * 关联通行记录
     *
     * @return void
     */
    public function record()
    {
        return $this->hasMany(Record::class, 'plot_id', 'id');
    }
}
