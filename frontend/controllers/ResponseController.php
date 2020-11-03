<?php

namespace frontend\controllers;

use TaskForce\Exception\TaskForceException;
use TaskForce\ResponseEntity;
use TaskForce\Rule\CustomerAccessRule;
use yii\filters\AccessControl;
use yii\web\Response;

class ResponseController extends SecureController
{
    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        $customerActions = ['confirm', 'cancel'];

        return [
            'accessCustomer' => [
                'class' => AccessControl::class,
                'only' => $customerActions,
                'rules' => [
                    ['actions' => $customerActions],
                ],
                'ruleConfig' => ['class' => CustomerAccessRule::class],
            ],
        ];
    }


    /**
     * Confirm response
     *
     * @param int $id
     * @return Response
     * @throws TaskForceException
     */
    public function actionConfirm(int $id): Response
    {
        $response = new ResponseEntity($id);
        $response->confirm();
        $taskId = $response->model->task->id;

        return $this->redirect(['/tasks/view', 'id' => $taskId]);
    }

    /**
     * Cancel response
     *
     * @param int $id
     * @return Response
     * @throws TaskForceException
     */
    public function actionCancel(int $id): Response
    {
        $response = new ResponseEntity($id);
        $response->cancel();
        $taskId = $response->model->task->id;

        return $this->redirect(['/tasks/view', 'id' => $taskId]);
    }

}
