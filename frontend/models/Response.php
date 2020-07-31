<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property string $created_at
 * @property int $rate
 * @property string $description
 * @property int $task_id
 * @property int|null $price
 * @property string $status
 *
 * @property Task $task
 */
class Response extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['rate', 'description', 'task_id'], 'required'],
            [['rate', 'task_id', 'price'], 'integer'],
            [['description', 'status'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'rate' => 'Rate',
            'description' => 'Description',
            'task_id' => 'Task ID',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return ResponseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResponseQuery(get_called_class());
    }
}
