<?php

namespace frontend\models;

use TaskForce\ResponseEntity;
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
 * @property int $user_id
 * @property Task $task
 */
class Response extends ActiveRecord
{
    use ExceptionOnFindFail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * @param ActiveRecord $task
     * @return array
     */
    public static function findByTask(ActiveRecord $task): array
    {
        $currentUserId = Yii::$app->user->identity->getId();

        if ($currentUserId === $task->customer_id) {
            $modelsResponse = self::findResponsesByTaskId($task->id);
        } else {
            $modelsResponse = self::findResponsesByTaskId($task->id, $currentUserId);
        }

        return $modelsResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['description', 'user_id', 'task_id'], 'required'],
            [['task_id', 'user_id', 'price'], 'integer'],
            [['description', 'status'], 'string'],
            [['status'], 'in', 'range' => ResponseEntity::LIST],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
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
            'user_id' => 'User ID',
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
     * @param int|null $currentUserID
     * @return array
     */
    public static function findResponsesByTaskId(int $id, int $currentUserID = null): array
    {
        $query = self::find()->select('r.*, p.user_id, p.avatar, p.rate, u.name')
            ->from('response r')->where(['task_id' => $id])
            ->join('LEFT JOIN', 'user as u', 'r.user_id = u.id')
            ->join('LEFT JOIN', 'profile as p', 'r.user_id = p.user_id');

        if ($currentUserID) {
            $query->Andwhere(['r.user_id' => $currentUserID]);
        }

        return $query->asArray()->all();
    }

    /**
     *
     * @param int $taskId
     * @param int $userId
     * @return array
     */
    public static function findByTaskIdUserId(int $taskId, int $userId): array
    {
        return self::find()->select('id')
            ->from('response r')->where(['task_id' => $taskId])->andWhere(['user_id' => $userId])->asArray()->all();
    }

    /**
     *
     * @param int $taskId
     * @return array
     */
    public static function findByTaskIdCurrentUserId(int $taskId): array
    {
        $currentUserId = Yii::$app->user->getId();

        return self::find()->select('id')
            ->from('response r')->where(['task_id' => $taskId])
            ->andWhere(['user_id' => $currentUserId])->asArray()->all();
    }
}
