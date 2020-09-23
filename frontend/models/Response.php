<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class Response extends ActiveRecord
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
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
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
     * @return ActiveQuery|TaskQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return ResponseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResponseQuery(get_called_class());
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public static function findResponsesByTaskId(int $id): array
    {
        return  self::find()->select('r.*, p.user_id, p.avatar, p.rate, u.name')
        ->from('response r')->where(['task_id' => $id])
        ->join('LEFT JOIN', 'user as u', 'r.user_id = u.id')
        ->join('LEFT JOIN', 'profile as p', 'r.user_id = p.user_id')
        ->asArray()->all();
    }

}
