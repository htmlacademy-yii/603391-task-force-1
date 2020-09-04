<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_notification".
 *
 * @property int $user_id
 * @property int $notification_id
 *
 * @property Notification $notification
 * @property User $user
 */
class UserNotification extends \yii\db\ActiveRecord
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
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::className(), 'targetAttribute' => ['notification_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
     * @return \yii\db\ActiveQuery|NotifiQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::className(), ['id' => 'notification_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
