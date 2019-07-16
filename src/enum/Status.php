<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-16
 * Time: 下午5:38
 */

namespace wodrow\yii2wtools\enum;


class Status
{
    const STATUS_ACTIVE = 10;
    const STATUS_DRAFT = 0;
    const STATUS_BLACK = -1;
    const STATUS_DEL = -10;

    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE => "正常",
            self::STATUS_DRAFT => "草稿",
            self::STATUS_BLACK => "黑名单",
            self::STATUS_DEL => "已删除",
        ];
    }
}