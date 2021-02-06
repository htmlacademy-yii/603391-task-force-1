<?php

namespace api\modules\v1\models\requests;

use frontend\models\Task;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class NewMessageRequest extends Model
{
    public string $task_id = '';
    public string $message = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'message'], 'required', 'message' => 'Обязательное поле'],
            ['message', 'trim'],
            [
                'task_id',
                'exist',
                'targetClass' => Task::class,
                'targetAttribute' => 'id',
                'message' => 'Task не найден'
            ],
            [
                'task_id',
                'validateUserImplication'
            ],
            ['message', 'string', 'min' => 1, 'tooShort' => 'Поле должно быть не менее 1 символа.'],
            ['message', 'string', 'max' => 512, 'tooLong' => 'Поле должно быть не более 512 символов.'],
        ];
    }

    /**
     * @param $attribute
     * @throws NotFoundHttpException
     */
    public function validateUserImplication($attribute)
    {
        if ($this->hasErrors()) {
            return;
        }
        $userId = Yii::$app->user->id;
        if (!$userId || !in_array($userId, Task::getBothUsers($this->$attribute))) {
            $this->addError($attribute, 'Нет прав на задачу.');
        }
    }
}