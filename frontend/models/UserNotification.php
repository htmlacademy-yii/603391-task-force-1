<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_notification".
 *
 * @property int $user_id
 * @property int $notification_id
 * @property Notification $notification
 * @property User $user
 */
class UserNotification extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_id'], 'required'],
            [['user_id', 'notification_id'], 'integer'],
            [['user_id', 'notification_id'], 'unique', 'targetAttribute' => ['user_id', 'notification_id']],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::class, 'targetAttribute' => ['notification_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'notification_id' => 'Notification ID',
        ];
    }

    /**
     * Gets query for [[Notification]].
     *
     * @return ActiveQuery|NotificationQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::class, ['id' => 'notification_id']);
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
     * @return UserNotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserNotificationQuery(get_called_class());
    }
}
