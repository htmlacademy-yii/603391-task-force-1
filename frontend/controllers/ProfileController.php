<?php

namespace frontend\controllers;

class ProfileController extends SecureController
{
    /**
     * Profile
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render(
            'index',
        );
    }
}