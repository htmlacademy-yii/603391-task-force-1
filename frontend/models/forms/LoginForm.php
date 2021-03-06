<?php

namespace frontend\models\forms;

use frontend\models\User;
use yii\base\Model;

/**
 *
 * @property-read mixed $user
 */
class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [ 'email','email' ],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @param $attribute
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }

}
