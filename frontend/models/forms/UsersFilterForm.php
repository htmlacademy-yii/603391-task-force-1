<?php

namespace frontend\models\forms;

use frontend\models\User;
use TaskForce\Provider\UserActiveDataProvider;
use TaskForce\SortingUsers;
use Yii;
use yii\base\Model;
use yii\db\Query;

class UsersFilterForm extends Model
{
    private const DEFAULT_MAX_ELEMENTS = 5;
    public bool $freeNow = false;
    public bool $onlineNow = false;
    public bool $feedbackExists = false;
    public bool $isFavorite = false;
    public string $searchName = '';
    public string $sortType = '';
    public ?int $category = null;

    /**
     * @return array
     */
    public function checkboxesLabels(): array
    {
        return [
            'freeNow' => 'Сейчас свободен',
            'onlineNow' => 'Сейчас онлайн',
            'feedbackExists' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'freeNow' => 'Сейчас свободен',
            'onlineNow' => 'Сейчас онлайн',
            'feedbackExists' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @return array
     */
    public function fieldsLabels(): array
    {
        return [
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['freeNow', 'onlineNow', 'feedbackExists', 'isFavorite', 'searchName', 'sortType', 'category'], 'safe'],
            [['searchName'], 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9ё_\s,]+$/']
        ];
    }

    /**
     * @param $params
     * @return \TaskForce\Provider\UserActiveDataProvider
     */
    public function search($params): UserActiveDataProvider
    {
        $query = User::findNewExecutors();
        $dataProvider = new UserActiveDataProvider(
            [
                'query' => $query,
                'Pagination' => [
                    'pageSize' => Yii::$app->params['maxPaginatorItems'] ?? self::DEFAULT_MAX_ELEMENTS,
                ],
            ]
        );

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $this->applyFilters($params, $query);
        $query->andFilterWhere(['LIKE', 'u.name', $this->searchName]);
        $this->applySort($params['sortType'] ?? '', $query);

        return $dataProvider;
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

    /**
     * Apply form filters
     * @param array $request
     * @param Query $query
     * @return Query|null
     */
    public static function applyFilters(array $request, Query $query): ?Query
    {
        // filter by category
        if (isset($request['CategoriesFilterForm']['categories'])) {

            $categoriesFilterForm = $request['CategoriesFilterForm'];
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

        if (isset($request['UsersFilterForm'])) {
            $usersFilters = $request['UsersFilterForm'];
        } else {
            return $query;
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
}
