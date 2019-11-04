<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-4
 * Time: 下午3:20
 */

namespace wodrow\yii2wtools\enum;


class Status
{
    const STATUS_ACTIVE = 10;

    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE => "正常",
        ];
    }
}