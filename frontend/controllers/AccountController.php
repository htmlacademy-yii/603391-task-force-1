<?php

namespace frontend\controllers;

class AccountController extends SecureController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'frontend\actions\AccountIndexAction',
            ],
        ];
    }
}
