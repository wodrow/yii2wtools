<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Idcard extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $idcard = $model->$attribute;
        $len = strlen($idcard);
        switch ($len){
            case 15:
                $r = "/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/";
                preg_match($r, $idcard, $arr);
                if (!$arr){
                    $this->addError($model, $attribute, $attribute . '身份证号[15位]错误');
                }
                break;
            case 18:
                $r = "/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/";
                preg_match($r, $idcard, $arr);
                if (!$arr){
                    $this->addError($model, $attribute, $attribute . '身份证号[18位]错误');
                }
                break;
            default:
                $this->addError($model, $attribute, $attribute . '长度必须是15或18位');
                break;
        }
    }
}