<?php

namespace frontend\controllers;

class WorkController extends SecureController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'add' => [
                'class' => 'frontend\actions\WorkAddAction',
            ],
            'remove' => [
                'class' => 'frontend\actions\WorkRemoveAction',
            ],
            'list' => [
                'class' => 'frontend\actions\WorkListAction',
            ],
        ];
    }
}
