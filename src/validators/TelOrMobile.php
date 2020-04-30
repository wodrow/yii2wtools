<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/30
 * Time: 12:09
 */

namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class TelOrMobile extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $val = $model->$attribute;
        $len = strlen($val);
        switch ($len){
            case 11:
                $mobile = $val;
                $r = "/^1[345678]{1}\d{9}$/";
                preg_match($r, $mobile, $arr);
                if (!$arr){
                    $this->addError($model, $attribute, $attribute . '手机号错误');
                }
                break;
            case 13:
                $tel = $val;
                $r = "/^\d{3}-\d{7,8}|\d{4}-\d{7,8}$/";
                preg_match($r, $tel, $arr);
                if (!$arr){
                    $this->addError($model, $attribute, $attribute . '座机号错误');
                }
                break;
            default:
                $this->addError($model, $attribute, $attribute . '长度必须是11位手机号或13位座机号');
                break;
        }
    }
}