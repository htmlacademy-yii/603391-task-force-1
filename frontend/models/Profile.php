<?php

namespace frontend\models;


use TaskForce\Constant\UserRole;
use TaskForce\Exception\TaskForceException;
use TaskForce\SortingUsers;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $address
 * @property string $birthday
 * @property string|null $about
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $messenger
 * @property string $avatar
 * @property int $rate
 * @property string $show
 * @property User $user
 */
class Profile extends ActiveRecord
{
    use ExceptionOnFindFail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['birthday'], 'safe'],
            [['user_id', 'rate', 'show'], 'integer'],
            [['about'], 'string'],
            [['address', 'skype', 'messenger', 'avatar'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],

            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'birthday' => 'Birthday',
            'about' => 'About',
            'user_id' => 'User ID',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'messenger' => 'Messenger',
            'avatar' => 'Avatar',
            'rate' => 'Rate',

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
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    /**
     * @param int $id
     * @return array|null
     */
    public static function findByUserId(int $id): ?array
    {
        return self::find()
            ->select(
                'u.role,p.about, p.id as profile_id, p.user_id, p.birthday,p.phone, p.messenger, p.skype,
             p.avatar, p.rate, u.email, u.city_id, u.date_login, u.name, u.date_add'
            )
            ->from('profile p')
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->where(['p.user_id' => $id])
            ->limit(1)
            ->asArray()->one();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public static function findProfileById(int $id): ?array
    {
        return self::find()
            ->select('p.*, p.birthday, p.avatar, p.rate, u.email, u.date_login, u.name, u.date_add')
            ->from('profile p')
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->where(['p.id' => $id])
            ->limit(1)
            ->asArray()->one();
    }
}
