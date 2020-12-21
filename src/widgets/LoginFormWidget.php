<?php

namespace TaskForce\widgets;

use frontend\models\forms\LoginForm;
use yii\base\Widget;

class LoginFormWidget extends Widget
{
    public LoginForm $loginForm;

    public function run(): ?string
    {
        parent::run();
        $this->loginForm = new LoginForm();

        return $this->render('@widgets/loginForm/view', ['loginForm'=> $this->loginForm ]);
    }
}
