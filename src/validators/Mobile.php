<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Mobile extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $mobile = $model->$attribute;
        if(strlen($mobile) == "11"){
            $r = "/^1[3456789]{1}\d{9}$/";
//            $r = "/^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/";
            preg_match($r, $mobile, $arr);
            if (!$arr){
                $this->addError($model, $attribute, $attribute . '手机号错误');
            }
        }else {
            $this->addError($model, $attribute, $attribute . '手机号长度必须是11位');
        }
    }
}