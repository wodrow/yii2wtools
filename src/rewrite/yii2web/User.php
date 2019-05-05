<?php
namespace wodrow\yii2wtools\rewrite\yii2web;

use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\members\wodrow\rewrite\yii2web
 * @property \common\models\db\User $identity
 */
class User extends \yii\web\User
{
    public $isInConsole = false;
    public $loginIp = '';
    public $loginMsg = '';

    /**
     * @param IdentityInterface $identity
     * @param int $duration
     * @return bool
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $user = $identity;
            $this->loginMsg .= "{$user->username}";
            if ($this->isInConsole){
                $this->loginIp = '127.0.0.1';
                $this->loginMsg .= " 登录IP {$this->loginIp} 在控制";
            }else{
                $this->loginIp = \Yii::$app->request->getUserIP();
                $this->loginMsg .= " 登录IP {$this->loginIp}";
            }
            if ($this->enableSession) {
                $this->loginMsg .= " 持续时间 {$duration}.";
            } else {
                $this->loginMsg .= ".";
            }
            $this->afterLogin($identity, false, $duration);
        }

        return !$this->getIsGuest();
    }
}