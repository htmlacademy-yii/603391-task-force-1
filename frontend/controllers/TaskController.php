<?php

namespace frontend\controllers;

use TaskForce\Rule\CustomerAccessRule;
use TaskForce\Rule\ExecutorAccessRule;
use yii\filters\AccessControl;

class TaskController extends SecureController
{
    public function actions()
    {
        return [
            'create' => [
                'class' => 'frontend\actions\TaskCreateAction',
            ],
            'response' => [
                'class' => 'frontend\actions\TaskResponseAction',
            ],
            'refuse' => [
                'class' => 'frontend\actions\TaskRefuseAction',
            ],
            'cancel' => [
                'class' => 'frontend\actions\TaskCancelAction',
            ],
            'complete' => [
                'class' => 'frontend\actions\TaskCompleteAction',
            ],
        ];
    }

    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        $customerActions = ['create', 'cancel', 'complete'];
        $executorActions = ['response', 'refuse'];

        return [
            'accessCustomer' => [
                'class' => AccessControl::class,
                'only' => $customerActions,
                'rules' => [
                    ['actions' => $customerActions],
                ],
                'ruleConfig' => ['class' => CustomerAccessRule::class],
            ],
            'accessExecutor' => [
                'class' => AccessControl::class,
                'only' => $executorActions,
                'rules' => [
                    ['actions' => $executorActions],
                ],
                'ruleConfig' => ['class' => ExecutorAccessRule::class],
            ],
        ];
    }
}