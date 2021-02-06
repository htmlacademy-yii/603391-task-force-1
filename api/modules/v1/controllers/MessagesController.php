<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\requests\NewMessageRequest;
use frontend\models\Event;
use frontend\models\Task;
use api\modules\v1\models\Message;
use TaskForce\Constant\NotificationType;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class MessagesController extends ApiController
{
    const NEW_MESSAGE = 'Новое сообщение в чате';
    public $modelClass = Message::class;
    public $enableCsrfValidation = false;

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

    public function prepareDataProvider(): ActiveDataProvider
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
     * @throws ServerErrorHttpException
     * @throws TaskForceException
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new NewMessageRequest();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->validate()) {
            foreach ($model->getErrors() as $key => $value) {
                throw new ServerErrorHttpException($key . ': ' . $value[0]);
            }
        }

        $chatMessage = new $this->modelClass;
        $chatMessage->message = $model->message;
        $chatMessage->task_id = $model->task_id;
        $chatMessage->user_id = Yii::$app->user->identity->getId();

        if (!$chatMessage->save()) {
            throw new ServerErrorHttpException('Error creating message');
        }
        $chatMessage->refresh();
        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);

        $task = Task::findOne($model->task_id);
        $event = new Event();
        $event->user_id = (Yii::$app->user->identity->getId()
            == $task->executor_id) ? $task->customer_id : $task->executor_id;
        $event->task_id = $model->task_id;
        $event->info = self::NEW_MESSAGE;
        $event->create(NotificationType::NEW_MESSAGE);

        return $chatMessage;
    }
}