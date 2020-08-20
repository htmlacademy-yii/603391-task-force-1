<?php

namespace frontend\models;

use TaskForce\Helpers\Utils;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $address
 * @property string $birthday
 * @property int $city_id
 * @property string|null $about
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $messenger
 * @property string $avatar
 * @property int $rate
 * @property string $role
 *
 * @property City $city
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
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
            [['address', 'birthday', 'city_id', 'user_id', 'avatar'], 'required'],
            [['birthday'], 'safe'],
            [['city_id', 'user_id', 'rate'], 'integer'],
            [['about', 'role'], 'string'],
            [['address', 'skype', 'messenger', 'avatar'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'city_id' => 'City ID',
            'about' => 'About',
            'user_id' => 'User ID',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'messenger' => 'Messenger',
            'avatar' => 'Avatar',
            'rate' => 'Rate',
            'role' => 'Role',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
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
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }


    /**
     * @return array|null
     */
    public static function findNewExecutors(): ?array
    {
        $query = new Query();
        $query->select(['p.*', 'u.name', 'u.date_login'])->from('profile p')
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->where("p.role = 'executor'")
            ->orderBy(['u.date_add' => SORT_DESC]);

        $models = $query->all();

        if (count($models)) {

            foreach ($models as $key => $element) {
                $query = new Query();
                $query->select('c.name')->from('specialisation s')
                    ->join('LEFT JOIN', 'category as c', 's.category_id = c.id')
                    ->where("profile_id = " . $element['id']);
                $models[$key]['categories'] = $query->all();


                $models[$key]['countTasks'] = Task::find()
                    ->where(["executor_id" => $element['id']])
                    ->count();

                $models[$key]['countReplies'] = Opinion::find()
                    ->where(['executor_id' => $element['id']])
                    ->count();

                $models[$key]['afterTime'] = Utils::timeAfter($element['date_login']);
            }
        }
        return $models;
    }


}
