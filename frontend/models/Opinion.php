<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "opinion".
 *
 * @property int $id
 * @property string $created_at
 * @property int $task_id
 * @property int $owner_id
 * @property int $executor_id
 * @property int $rate
 * @property string $description
 * @property bool $done
 *
 *
 * @property User $task
 * @property User $executor
 * @property User $owner
 *
 */
class Opinion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['owner_id', 'executor_id', 'task_id','rate', 'description', 'done'], 'required'],
            [['owner_id', 'executor_id','task_id', 'rate'], 'integer'],
            [['description'], 'string'],
            [['done'], 'boolean'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'created_at' => 'Created At',
            'owner_id' => 'Owner ID',
            'executor_id' => 'Executor ID',
            'rate' => 'Rate',
            'description' => 'Description',
            'done' => 'Done',
        ];
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
     * Gets query for [[Owner]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * {@inheritdoc}
     * @return OpinionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpinionQuery(get_called_class());
    }


    /**
     *
     * @param int $id
     * @return array|Opinion[]
     */
    public static function findOpinionsByUserId(int $id): array
    {
        return self::find()->select('o.*, t.name as taskName,t.id as task_id, u.name as userName, p.*')
            ->from('opinion o')
            ->join('LEFT JOIN', 'profile as p', 'o.owner_id = p.user_id')
            ->join('LEFT JOIN', 'user as u', 'o.owner_id = u.id')
            ->join('LEFT JOIN', 'task as t', 'o.owner_id = t.customer_id')
            ->where(['o.executor_id' => $id])->asArray()->all();
    }

    /**
     *
     * @param int $id
     * @return int
     */
    public static function findCountOpinionsByUserId(int $id): ?int
    {
        return self::find()->where(['executor_id'=>$id])->count();
    }


}
