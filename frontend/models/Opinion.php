<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "opinion".
 *
 * @property int $id
 * @property string $created_at
 * @property int $owner_id
 * @property int $executor_id
 * @property int $rate
 * @property string $description
 *
 * @property User $executor
 * @property User $owner
 */
class Opinion extends \yii\db\ActiveRecord
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
            [['owner_id', 'executor_id', 'rate', 'description'], 'required'],
            [['owner_id', 'executor_id', 'rate'], 'integer'],
            [['description'], 'string'],
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
            'created_at' => 'Created At',
            'owner_id' => 'Owner ID',
            'executor_id' => 'Executor ID',
            'rate' => 'Rate',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
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
        return self::find()->select('o.*, t.name as taskName, u.name as userName, p.*')
            ->from('opinion o')
            ->join('LEFT JOIN', 'profile as p', 'o.executor_id = p.user_id')
            ->join('LEFT JOIN', 'user as u', 'o.executor_id = u.id')
            ->join('LEFT JOIN', 'task as t', 'o.executor_id = t.customer_id')
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
