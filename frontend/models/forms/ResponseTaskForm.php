<?php

namespace frontend\models\forms;

use Exception;
use frontend\models\Response;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\Model;

class ResponseTaskForm extends Model
{
    public string $payment = '';
    public string $comment = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['payment', 'integer'],
            ['payment', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['comment', 'trim'],
            [['payment'], 'required', 'message' => 'Поле не заполнено.'],
        ];
    }

    /**
     * @param $taskId
     * @param $userId
     * @throws TaskForceException
     * @throws \Throwable
     */
    public function createResponse($taskId, $userId)
    {
        $response = new Response();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $response->description = $this->comment;
            $response->price = $this->payment;
            $response->task_id = $taskId;
            $response->status = \TaskForce\Response::STATUS_NEW;
            $response->user_id = $userId;
            $response->insert();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new TaskForceException(
                "Ошибка создания отклика пользователя ID #$userId для задачи c ID #$taskId"
            );
        }

    }





}
