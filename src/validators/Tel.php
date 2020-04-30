<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Mobile extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $tel = $model->$attribute;
        if(strlen($tel) == "13"){
            $r = "/^\d{3}-\d{7,8}|\d{4}-\d{7,8}$/";
            preg_match($r, $tel, $arr);
            if (!$arr){
                $this->addError($model, $attribute, $attribute . '座机号错误');
            }
        }else {
            $this->addError($model, $attribute, $attribute . '座机号长度必须是13位');
        }
    }
}