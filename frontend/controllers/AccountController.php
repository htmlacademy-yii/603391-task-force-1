<?php

namespace frontend\controllers;

class AccountController extends SecureController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'frontend\actions\AccountIndexAction',
            ],
        ];
    }
}
