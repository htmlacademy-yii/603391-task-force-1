<?php

namespace frontend\controllers;

use TaskForce\Exception\TaskForceException;

class ProfileController extends SecureController
{
    /**
     * Profile
     *
     * @return string
     * @throws TaskForceException
     */
    public function actionIndex(): string
    {
        return $this->render(
            'index',
        );
    }


}
