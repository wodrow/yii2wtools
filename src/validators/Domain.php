<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/30
 * Time: 11:56
 */

namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Domain extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $domain = $model->$attribute;
        $r = "/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(/.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+/.?/";
        preg_match($r, $domain, $arr);
        if (!$arr){
            $this->addError($model, $attribute, $attribute . '汉字错误');
        }
    }
}