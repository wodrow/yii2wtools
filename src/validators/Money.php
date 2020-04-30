<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/30
 * Time: 11:56
 */

namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Money extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $money = $model->$attribute;
        $r = "/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/";
        preg_match($r, $money, $arr);
        if (!$arr){
            $this->addError($model, $attribute, $attribute . '汉字错误');
        }
    }
}