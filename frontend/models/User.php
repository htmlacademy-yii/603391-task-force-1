<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $date_add
 * @property string $date_login
 *
 * @property Chat[] $chats
 * @property Chat[] $chats0
 * @property Favorite[] $favorites
 * @property File[] $favorites0
 * @property Opinion[] $opinions
 * @property Opinion[] $opinions0
 * @property Profile[] $profiles
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserNotification[] $userNotifications
 * @property Notification[] $notifications
 * @property Work[] $works
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'password'], 'required'],
            [['date_add', 'date_login'], 'safe'],
            [['email', 'name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
            'date_add' => 'Date Add',
            'date_login' => 'Date Login',
        ];
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery|ChatQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['consumer_id' => 'id']);
    }

    /**
     * Gets query for [[Chats0]].
     *
     * @return \yii\db\ActiveQuery|ChatQuery
     */
    public function getChats0()
    {
        return $this->hasMany(Chat::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery|FavoriteQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites0]].
     *
     * @return \yii\db\ActiveQuery|FileQuery
     */
    public function getFavorites0()
    {
        return $this->hasMany(File::className(), ['id' => 'favorite_id'])->viaTable('favorite', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery|OpinionQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions0]].
     *
     * @return \yii\db\ActiveQuery|OpinionQuery
     */
    public function getOpinions0()
    {
        return $this->hasMany(Opinion::className(), ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery|ProfileQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserNotifications]].
     *
     * @return \yii\db\ActiveQuery|UserNotificationQuery
     */
    public function getUserNotifications()
    {
        return $this->hasMany(UserNotification::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return \yii\db\ActiveQuery|NotificationQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['id' => 'notification_id'])->viaTable('user_notification', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Works]].
     *
     * @return \yii\db\ActiveQuery|WorkQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
