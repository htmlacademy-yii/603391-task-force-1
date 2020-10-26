<?php

namespace frontend\controllers;

use TaskForce\Exception\TaskForceException;
use yii\web\Response;

class ResponseController extends SecureController
{

    /**
     * Confirm response
     *
     * @param int $id
     * @return Response
     * @throws TaskForceException
     */
    public function actionConfirm(int $id): Response
    {
        $response = new \TaskForce\Response($id);
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
        $response = new \TaskForce\Response($id);
        $response->cancel();
        $taskId = $response->model->task->id;

        return $this->redirect(['/tasks/view', 'id' => $taskId]);
    }

}
