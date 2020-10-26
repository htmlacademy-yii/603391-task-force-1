<?php

namespace frontend\models;

use TaskForce\Constant\UserRole;
use TaskForce\SortingUsers;
use Yii;
use yii\base\InvalidConfigException;
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
 * @property-read void $authKey
 * @property Work[] $works
 */
class User extends ActiveRecord implements IdentityInterface
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

    public function getAuthKey()
    {
        // Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // Implement validateAuthKey() method.
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
     * Gets query for [[Favorites]].
     *
     * @return ActiveQuery|FavoriteQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites0]].
     *
     * @return ActiveQuery|FileQuery
     * @throws InvalidConfigException
     */
    public function getFavorites0()
    {
        return $this->hasMany(File::class, ['id' => 'favorite_id'])->viaTable('favorite', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return ActiveQuery|OpinionQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions0]].
     *
     * @return ActiveQuery|OpinionQuery
     */
    public function getOpinions0()
    {
        return $this->hasMany(Opinion::class, ['owner_id' => 'id']);
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
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery|TaskQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
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
     * Gets query for [[Notifications]].
     *
     * @return ActiveQuery|NotificationQuery
     * @throws InvalidConfigException
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::class, ['id' => 'notification_id'])->viaTable(
            'user_notification',
            ['user_id' => 'id']
        );
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
            ->select(['p.about', 'p.avatar','p.rate','u.role','p.id', 'u.name', 'u.date_login'])
            ->join('LEFT JOIN', 'profile as p', 'u.id = p.user_id')
            ->join('LEFT JOIN', ['t' => $countTasks], 'p.user_id = t.executor_id')
            ->where(['u.role' => UserRole::EXECUTOR]);

        $query = self::applyFilters($request, $query);
        $query = self::applySort($sortType, $query);

        return $query;
    }

    /**
     * Apply form filters
     * @param array $request
     * @param Query $query
     * @return Query|null
     */
    public static function applyFilters(array $request, Query $query): ?Query
    {
        if (strlen($request['UsersFilterForm']['searchName']) > 0) {
            $query->andWhere(sprintf('u.name LIKE \'%s\'', '%' . $request['UsersFilterForm']['searchName'] . '%'));

            return $query;
        }

        // filter by category
        if (isset($request['CategoriesFilterForm']['categories'])) {
            $list = [];
            foreach ($request['CategoriesFilterForm']['categories'] as $key => $item) {
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
        if ($request['UsersFilterForm']['freeNow']) {
            $subQuery1 = (new Query())
                ->select('id')->from('task t')
                ->where('t.executor_id = p.user_id');
            $query->andWhere(['not exists', $subQuery1]);
        }

        // filter by 'Online Now'
        if ($request['UsersFilterForm']['onlineNow']) {
            $query->andWhere('u.date_login > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        }

        // filter by 'Reviews'
        if ($request['UsersFilterForm']['feedbackExists']) {
            $subQuery2 = (new Query())
                ->select('id')->from('opinion o')
                ->where('o.executor_id = p.user_id');
            $query->andWhere(['exists', $subQuery2]);
        }

        // filter by 'Favorite'
        if ($request['UsersFilterForm']['isFavorite']) {
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

}
