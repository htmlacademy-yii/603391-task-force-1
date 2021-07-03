<?php

namespace frontend\models;

use TaskForce\Constant\UserRole;
use TaskForce\Helpers\Declination;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $role
 * @property string $password_reset_token
 * @property string $date_add
 * @property string $date_login
 * @property int $city_id;
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
 * @property-read void $authKey
 * @property Work[] $works
 * @property string $auth_key
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ExceptionOnFindFail;

    const STATUS_ACTIVE = 11;

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
            [['email', 'name', 'password_reset_token'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['role'], 'in', 'range' => UserRole::LIST],
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
            'role' => 'Role',
            'date_add' => 'Date Add',
            'date_login' => 'Date Login',
        ];
    }

    /**
     * @param int|string $id
     * @return User|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement findIdentityByAccessToken() method.
    }

    /**
     * @return array|int|mixed|string|null
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAfterTime(): string
    {
        return Declination::getTimeAfter($this->date_login);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getCountReplies(): int
    {
        return Opinion::findCountOpinionsByUserId($this->id);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getCountTasks(): int
    {
        return Task::findCountByUserId($this->id);
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // Implement getAuthKey() method.
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // Implement validateAuthKey() method.
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::class, ['consumer_id' => 'id']);
    }

    /**
     * Gets query for [[Chats0]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChats0()
    {
        return $this->hasMany(Chat::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return ActiveQuery|ProfileQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserNotifications]].
     *
     * @return ActiveQuery|UserNotificationQuery
     */
    public function getUserNotifications()
    {
        return $this->hasMany(UserNotification::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Works]].
     *
     * @return ActiveQuery|WorkQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Add data
     * @return Query|null
     */
    public static function findNewExecutors(): ?Query {
        $countTasks = Task::find()
            ->select('executor_id, count(*) AS task_count')
            ->from('task t')
            ->groupBy('executor_id');

        $query = new Query();
        $query->from('user u')
            ->select(
                ['p.about', 'p.avatar', 'p.rate', 'u.role', 'p.id as profile_id', 'u.id', 'u.name', 'u.date_login']
            )
            ->join('LEFT JOIN', 'profile as p', 'u.id = p.user_id')
            ->join('LEFT JOIN', ['t' => $countTasks], 'p.user_id = t.executor_id')
            ->where(['u.role' => UserRole::EXECUTOR])->andWhere(['not', ['p.id' => null]]);

        return $query;
    }



    /**
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function currentUser()
    {
        return User::findOrFail(Yii::$app->user->identity->getId(), 'Пользователь не найден');
    }

    /**
     * @throws \yii\web\NotFoundHttpException
     */
    public static function updateUserRoleBySpecialisations()
    {
        $profileId = Profile::currentProfile();
        $user = User::currentUser();
        $specialisations = Specialization::findItemsByProfileId((int)$profileId);
        if (count($specialisations) === 0) {
            $user->role = UserRole::CUSTOMER;
        } else {
            $user->role = UserRole::EXECUTOR;
        }
        $user->update();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @param $token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @param $token
     * @return User|null
     */
    public static function findByPasswordResetToken($token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }
}
