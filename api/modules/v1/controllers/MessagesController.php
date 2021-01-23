<?php

namespace api\modules\v1\controllers;

use frontend\models\Event;
use frontend\models\Task;
use api\modules\v1\models\Message;
use TaskForce\EventEntity;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;
    public $enableCsrfValidation = false;

    function behaviors()
    {
        $behaviors = parent::behaviors();

        return array_merge(
            $behaviors,
            [
                'corsFilter' => [
                    'class' => Cors::class,
                    'cors' => [
                        'Access-Control-Request-Method' => ['GET, POST, OPTIONS'],
                        'Access-Control-Allow-Credentials' => true,
                        'Access-Control-Max-Age' => 3600,
                    ]
                ],
            ]
        );
    }

    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['create'],
            $actions['update'],
            $actions['delete'],
        );
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        return new ActiveDataProvider(
            [
                'query' => $this->modelClass::find()->andWhere(
                    [
                        'task_id' => Yii::$app->request->get('task_id')
                    ]
                ),
                'pagination' => false
            ]
        );
    }

    /**
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws TaskForceException
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $userId = Yii::$app->user->identity->getId();
        $taskId = $request->post('task_id');
        $task = Task::findOrFail( $taskId,"Task with ID $taskId not found.");
        if (!($userId === $task->executor_id || $userId === $task->customer_id)) {
            throw new ForbiddenHttpException('No access rights ' . $userId);
        }
        $chatMessage = new $this->modelClass;
        $chatMessage->message = $request->post('message');
        $chatMessage->task_id = $taskId;
        $chatMessage->user_id = (int)$userId;
        if ($chatMessage->save()) {
            $chatMessage->refresh();
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);

            $event = new EventEntity(EventEntity::GROUP_MESSAGE_ID);
            $event->user_id = (Yii::$app->user->identity->getId() == $task->executor_id)?$task->customer_id:$task->executor_id;
            $event->task_id = $taskId;
            $event->info = 'Новое сообщение в чате';
            Event::createNotification($event);

        } else {
            throw new ServerErrorHttpException('Error creating message');
        }

        return $chatMessage;
    }
}