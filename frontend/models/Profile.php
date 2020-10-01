<?php

namespace frontend\models;


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
class Profile extends ActiveRecord
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
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
     * @return ActiveQuery|CityQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
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
     * Применить фильры формы
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

        // фильтрация по категории
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

        // фильтрация по 'Сейчас свободен'
        if ($request['UsersFilterForm']['freeNow']) {
            $subQuery1 = (new Query())
                ->select('id')->from('task t')
                ->where('t.executor_id = p.user_id');
            $query->andWhere(['not exists', $subQuery1]);
        }

        // фильтрация по 'Сейчас онлайн'
        if ($request['UsersFilterForm']['onlineNow']) {
            $query->andWhere('u.date_login > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        }

        // фильтрация по 'Есть отзывы'
        if ($request['UsersFilterForm']['feedbackExists']) {
            $subQuery2 = (new Query())
                ->select('id')->from('opinion o')
                ->where('o.executor_id = p.user_id');
            $query->andWhere(['exists', $subQuery2]);
        }

        // фильтрация по 'В избранном'
        if ($request['UsersFilterForm']['isFavorite']) {
            $subQuery3 = (new Query())
                ->select('favorite_id')->from('favorite f')
                ->where('f.favorite_id = p.user_id');
            $query->andWhere(['exists', $subQuery3]);
        }

        return $query;
    }


    /**
     * Сортировка
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


    /**
     * Дополнить данными
     * @param array $request Запрос
     * @param string $sortType Тип сортировки
     * @return array|null
     */
    public static function findNewExecutors(array $request, string $sortType): ?Query
    {

        $countTasks = Task::find()
            ->select('executor_id,count(*) AS task_count')
            ->from('task t')
            ->groupBy('executor_id');

        $query = new Query();
        $query->from('profile p')
            ->select(['p.*', 'u.name', 'u.date_login' ])
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->join('LEFT JOIN', ['t' => $countTasks], 'p.user_id = t.executor_id')
            ->where("p.role = 'executor'");

        $query = self::applyFilters($request, $query);
        $query = self::applySort($sortType, $query);

        return $query;
    }


    /**
     * @param int $id
     * @return array|null
     */
    public static function findProfileByUserId(int $id): ?array
    {
        return self::find()
            ->select('p.*, p.birthday, p.avatar, p.rate, u.email, u.date_login, u.name, u.date_add')
            ->from('profile p')
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->where(['p.user_id' => $id])
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
            ->asArray()->one();
    }


}
