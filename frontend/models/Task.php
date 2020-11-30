<?php

namespace frontend\models;

use frontend\models\forms\TasksFilterForm;
use TaskForce\Constant\MyTask;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use TaskForce\TaskEntity;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $category_id
 * @property string $status
 * @property string $address
 * @property float $lat
 * @property float $lng
 * @property string $city_id
 * @property int $budget
 * @property string $expire
 * @property string $date_add
 * @property int|null $executor_id
 * @property int $customer_id
 *
 * @property Chat[] $chats
 * @property File[] $files
 * @property Response[] $responses
 * @property Category $category
 * @property User $customer
 * @property-read ActiveQuery|CategoryQuery $city
 * @property User $executor
 */
class Task extends ActiveRecord
{
    use ExceptionOnFindFail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['name', 'description', 'category_id', 'status', 'budget', 'date_add', 'customer_id'],
                'required'
            ],
            [['description'], 'string'],
            [['category_id', 'budget', 'executor_id', 'customer_id'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['expire', 'date_add'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['address'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['city_id'], 'integer'],
            [['status'], 'in', 'range' => TaskEntity::STATUSES],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'address' => 'Address',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'city_id' => 'City ID',
            'budget' => 'Budget',
            'expire' => 'Expire',
            'date_add' => 'Date Add',
            'executor_id' => 'Executor ID',
            'customer_id' => 'Customer ID',
        ];
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return ActiveQuery|ChatQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery|FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery|ResponseQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery|CategoryQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    /**
     * Find task with NEW status
     * @param array $request
     * @return Query
     * @throws TaskForceException
     */
    public static function findNewTask(array $request = []): Query
    {
        $isLoggedUser = Yii::$app->user->identity;

        $query = new Query();
        $list = [];
        if (isset($request['CategoriesFilterForm']['categories'])) {
            foreach ($request['CategoriesFilterForm']['categories'] as $key => $item) {
                if ($item) {
                    $list[] = sprintf("'%s'", $key);
                }
            }
        }

        $session = Yii::$app->session;
        $currentCityId = $session['current_city_id'] ?? $isLoggedUser->city_id;

        $query->select(['t.*', 'c.name as cat_name', 'c.icon as icon'])->from('task t')
            ->join('LEFT JOIN', 'category as c', 't.category_id = c.id')
            ->where(['t.status' => TaskEntity::STATUS_NEW]);

        if ($isLoggedUser) {
            $query->andWhere(
                [
                    'or',
                    ['t.city_id' => Yii::$app->user->identity->city_id],
                    ['t.city_id' => $currentCityId],
                    ['t.city_id' => null]
                ]
            );
        }

        if (!empty($list)) {
            $categoryList = sprintf('c.id in (%s)', implode(",", $list));
            $query->andWhere($categoryList);
        }

        $requestFilterForm = $request['TasksFilterForm'];
        $searchName = $requestFilterForm['searchName'] ?? '';
        if (strlen($searchName) > 0) {
            $query->andWhere(['LIKE', 't.name', $searchName, false]);
        }

        if (isset($requestFilterForm['withoutExecutor'])
            && $requestFilterForm['withoutExecutor'] === '1') {
            $query->andWhere('t.executor_id IS NULL');
        }

        if (isset($requestFilterForm['remoteWork'])
            && $requestFilterForm['remoteWork'] === '1') {
            $query->andWhere('t.lat IS NULL AND t.lng IS NULL');
        }

        if (isset($requestFilterForm['timeInterval'])
            && $requestFilterForm['timeInterval'] !== TasksFilterForm::FILTER_ALL_TIME) {
            $datetime = TasksFilterForm::timeBeforeInterval($requestFilterForm['timeInterval']);
            $query->andWhere("t.date_add > STR_TO_DATE('$datetime','%Y-%m-%d %H:%i:%s')");
        }

        return $query->orderBy(['date_add' => SORT_DESC]);
    }

    /**
     * Find Task By ID
     * @param int|null $id
     * @return array
     * @throws TaskForceException
     * @throws NotFoundHttpException
     */
    public static function findTaskTitleInfoByID(int $id = null): ?array
    {
        $query = new Query();

        $query->select(['t.*', 'c.name as cat_name', 'c1.city', 'c.icon as icon'])->from('task t')
            ->join('LEFT JOIN', 'category as c', 't.category_id = c.id')
            ->join('LEFT JOIN', 'city as c1', 't.city_id = c1.id')
            ->where(['t.id' => $id])
            ->limit(1);

        $model = $query->one();

        if ($model) {
            $model['afterTime'] = Declination::getTimeAfter((string)$model['date_add']);
        }

        if (!$model) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        return $model;
    }

    /**
     * @param int $id
     * @return int|null
     * @throws TaskForceException
     */
    public static function findCountByUserId(int $id): ?int
    {
        if ($id && ($id < 1)) {
            throw new TaskForceException('Не задан ID пользователя');
        }

        return self::find()->where(['id' => $id])->andWhere(['status' => TaskEntity::STATUS_COMPLETE])->count();
    }

     public static function getTaskByStatus(string $filterRequest)
    {
        $userId = Yii::$app->user->identity->getId();

        return self::find()->select(['t.*', 'c.name as cat_name', 'u.name as user_name', 'p.avatar','p.rate'])
            ->from('task t')
            ->join('LEFT JOIN', 'category as c', 't.category_id = c.id')
            ->join('LEFT JOIN', 'user as u', 't.customer_id = u.id')
            ->join('LEFT JOIN', 'profile as p', 't.customer_id = p.user_id')
            ->where(['or', ['t.customer_id' => $userId], ['t.executor_id' => $userId]])
            ->andWhere(['status' => MyTask::STATUS_BY_FILTER[$filterRequest]])->asArray()->all();
    }
}
