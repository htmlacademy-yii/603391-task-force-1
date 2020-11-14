<?php

namespace frontend\controllers;

class MyListController extends SecureController
{
    /**
     * My Task list
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render(
            'index',
        );
    }

}
