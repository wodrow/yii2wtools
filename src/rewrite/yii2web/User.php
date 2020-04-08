<?php
namespace wodrow\yii2wtools\rewrite\yii2web;

use yii\web\IdentityInterface;

/**
 * Class User
 * @property IdentityInterface $identity
 */
class User extends \yii\web\User
{
    public $isInConsole = false;
    public $loginIp = '';

    /**
     * @param IdentityInterface $identity
     * @param int $duration
     * @return bool
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            if ($this->isInConsole){
                $this->loginIp = '0.0.0.0';
            }else{
                $this->loginIp = \Yii::$app->request->getUserIP();
            }
            $this->afterLogin($identity, false, $duration);
        }

        return !$this->getIsGuest();
    }
}