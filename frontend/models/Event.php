<?php

namespace frontend\models;

use TaskForce\Constant\NotificationType;
use TaskForce\Exception\TaskForceException;
use TaskForce\EventNotificationService;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $date
 * @property int $user_id
 * @property int $notification_id
 * @property int $task_id
 * @property string|null $info
 * @property int|null $viewed
 *
 * @property NotificationType $notification
 * @property Task $task
 * @property User $user
 */
class Event extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date', 'user_id', 'notification_id', 'task_id'], 'required'],
            [['date'], 'safe'],
            [['user_id', 'notification_id', 'task_id', 'viewed'], 'integer'],
            [['info'], 'string', 'max' => 255],
            [
                ['notification_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Notification::class,
                'targetAttribute' => ['notification_id' => 'id']
            ],
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
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'user_id' => 'User ID',
            'notification_id' => 'Notification ID',
            'task_id' => 'Task ID',
            'info' => 'Info',
            'viewed' => 'Viewed',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function findEventsForUser(int $userId): array
    {
        return self::find()
            ->select(['notification_id,task_id, user_id, t.name as title,info, COUNT(*) AS cnt'])
            ->join('LEFT JOIN', 'notification n', 'notification_id = n.id')
            ->join('LEFT JOIN', 'task t', 'task_id = t.id')
            ->where(['user_id' => $userId, 'viewed' => 0])
            ->groupBy(['notification_id', 'task_id'])
            ->orderBy(['date' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();
    }

    /**
     * @param int $typeId
     * @throws TaskForceException
     */
    public function create(int $typeId): void
    {
        if (!in_array($typeId, NotificationType::LIST)) {
            throw new TaskForceException('Не верный тип уведомления');
        }
        $this->notification_id = $typeId;
        $this->viewed = 0;
        $this->date = new Expression('NOW()');

        if (!$this->save()) {
            throw new TaskForceException('Ошибка создания уведомления.');
        }

        EventNotificationService::sendEmail($this);
    }

    public static function findNewMessagesByTask(int $taskId): int
    {
        return self::find()->where(
            [
                'user_id' => Yii::$app->user->identity->getId(),
                'task_id' => $taskId,
                'viewed' => false,
                'notification_id' => NotificationType::NEW_MESSAGE
            ]
        )->count();
    }
}
