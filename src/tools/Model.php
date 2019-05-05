<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-4-17
 * Time: 下午4:03
 */

namespace wodrow\yii2wtools\tools;


class Model
{
    /**
     * 获取 Model 错误信息中的 第一条，无错误时 返回 null
     * @param \yii\base\Model $model
     * @return mixed|string
     */
    public static function getModelError($model) {
        $errors = $model->getErrors();    //得到所有的错误信息
        if(!is_array($errors)) return '';
        $firstError = array_shift($errors);
        if(!is_array($firstError)) return '';
        return array_shift($firstError);
    }
}