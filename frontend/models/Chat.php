<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int $consumer_id
 * @property int $executor_id
 * @property int $task_id
 * @property string $message
 * @property string $created_at
 *
 * @property Task $task
 * @property User $consumer
 * @property User $executor
 */
class Chat extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['consumer_id', 'executor_id', 'task_id', 'message'], 'required'],
            [['consumer_id', 'executor_id', 'task_id'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['consumer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['consumer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'consumer_id' => 'Consumer ID',
            'executor_id' => 'Executor ID',
            'task_id' => 'Task ID',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[Consumer]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getConsumer()
    {
        return $this->hasOne(User::class, ['id' => 'consumer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * {@inheritdoc}
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChatQuery(get_called_class());
    }
}
