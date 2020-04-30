<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class ChineseCharacters extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $hanzi = $model->$attribute;
        $r = "/^[\u4e00-\u9fa5]{0,}$/";
        preg_match($r, $hanzi, $arr);
        if (!$arr){
            $this->addError($model, $attribute, $attribute . '汉字错误');
        }
    }
}