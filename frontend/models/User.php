<?php

namespace frontend\models;

use TaskForce\Constant\UserRole;
use TaskForce\SortingUsers;
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

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

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
     * @param array $request request
     * @param string $sortType sorting type
     * @return array|null
     */
    public static function findNewExecutors(array $request, string $sortType): ?Query
    {
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

        $query = self::applyFilters($request, $query);

        return self::applySort($sortType, $query);
    }

    /**
     * Apply form filters
     * @param array $request
     * @param Query $query
     * @return Query|null
     */
    public static function applyFilters(array $request, Query $query): ?Query
    {
        if (!isset($request['UsersFilterForm'])) {
            return $query;
        }

        $usersFilters = $request['UsersFilterForm'];
        if (strlen($usersFilters['searchName']) > 0) {
            $query->andWhere(['LIKE', 'u.name', $usersFilters['searchName']]);

            return $query;
        }

        // filter by category
        $categoriesFilterForm = $request['CategoriesFilterForm'];
        if (isset($categoriesFilterForm['categories'])) {
            $list = [];
            foreach ($categoriesFilterForm['categories'] as $key => $item) {
                if ($item) {
                    $list[] = sprintf("'%s'", $key);
                }
            }

            $subQuery = (new Query())
                ->select('category_id')->from('specialization s')
                ->where('s.profile_id = p.id');

            if (!empty($list)) {
                $categoryList = sprintf('s.category_id in (%s)', implode(",", $list));
                $subQuery->andWhere($categoryList);
                $query->andFilterWhere(['exists', $subQuery]);
            }
        }

        // filtering by 'Free Now'
        if ((bool)$usersFilters['freeNow']) {
            $subQuery1 = (new Query())
                ->select('id')->from('task t')
                ->where('t.executor_id = p.user_id');
            $query->andWhere(['not exists', $subQuery1]);
        }

        // filter by 'Online Now'
        if ((bool)$usersFilters['onlineNow']) {
            $query->andWhere('u.date_login > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        }

        // filter by 'Reviews'
        if ((bool)$usersFilters['feedbackExists']) {
            $subQuery2 = (new Query())
                ->select('id')->from('opinion o')
                ->where('o.executor_id = p.user_id');
            $query->andWhere(['exists', $subQuery2]);
        }

        // filter by 'Favorite'
        if ((bool)$usersFilters['isFavorite']) {
            $subQuery3 = (new Query())
                ->select('favorite_id')->from('favorite f')
                ->where('f.favorite_id = p.user_id');
            $query->andWhere(['exists', $subQuery3]);
        }

        return $query;
    }

    /**
     * sorting
     * @param string $sortType
     * @param Query $query
     * @return Query
     */
    public static function applySort(string $sortType, Query $query): Query
    {
        switch ($sortType) {
            case SortingUsers::SORT_BY_RATING:
                $query->orderBy(['rate' => SORT_DESC]);
                break;
            case SortingUsers::SORT_BY_COUNT_TASK:
                $query->orderBy(['task_count' => SORT_DESC]);
                break;
            case SortingUsers::SORT_BY_POPULARITY:
                $query->orderBy(['show' => SORT_DESC]);
                break;
            default:
                $query->orderBy(['date_add' => SORT_DESC]);
        }

        return $query;
    }

    public static function currentUser()
    {
        return  User::findOrFail(Yii::$app->user->identity->getId(), 'Пользователь не найден');
    }

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

}
