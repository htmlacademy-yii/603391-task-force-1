<?php

namespace frontend\models\forms;

use DateTime;
use Exception;
use frontend\models\Task;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class TasksFilterForm extends Model
{
    public const FILTER_DAY = 'day';
    public const FILTER_WEEK = 'week';
    public const FILTER_MONTH = 'month';
    public const FILTER_ALL_TIME = 'all';

    public const FILTER_DAY_NAME = 'За день';
    public const FILTER_WEEK_NAME = 'За неделю';
    public const FILTER_MONTH_NAME = 'За месяц';
    public const FILTER_ALL_TIME_NAME = 'За все время';

    private const ENABLE_VALUE = '1';
    const DEFAULT_MAX_ELEMENTS = 5;

    public bool $withoutExecutor = false;
    public bool $remoteWork = false;
    public string $timeInterval = '';
    public string $searchName = '';
    public ?int $category = null;

    /**
     * @return string[]
     */
    public static function getIntervalList()
    {
        return [
            self::FILTER_DAY => self::FILTER_DAY_NAME,
            self::FILTER_WEEK => self::FILTER_WEEK_NAME,
            self::FILTER_MONTH => self::FILTER_MONTH_NAME,
            self::FILTER_ALL_TIME => self::FILTER_ALL_TIME_NAME,
        ];
    }

    /**
     * @return array
     */
    public function checkboxesLabels(): array
    {
        return [
            'withoutExecutor' => 'Без исполнителя',
            'remoteWork' => 'Удаленная работа',
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'withoutExecutor' => 'Без исполнителя',
            'remoteWork' => 'Удаленная работа',
            'timeInterval' => 'Период',
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [['withoutExecutor', 'remoteWork', 'timeInterval', 'searchName','category'], 'safe'],
            [['searchName'], 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9ё_\s,]+$/']
        ];
    }

    /**
     * @param string $interval
     * @return string|null
     * @throws TaskForceException
     * @throws Exception
     */
    public static function timeBeforeInterval(string $interval): ?string
    {
        $intervalDiff = [
            self::FILTER_DAY => '-1 day',
            self::FILTER_WEEK => '-1 week',
            self::FILTER_MONTH => '-1 month',
        ];

        if (!isset($intervalDiff[$interval])) {
            throw new TaskForceException('Unknown interval name');
        }
        $date = new DateTime($intervalDiff[$interval]);

        return $date->format('Y-m-d h:i:s');
    }

    /**
     * @throws \TaskForce\Exception\TaskForceException
     */
    public function search($params)
    {
        $query = Task::findNewTask();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'Pagination' => [
                    'pageSize' => Yii::$app->params['maxPaginatorItems'] ?? self::DEFAULT_MAX_ELEMENTS,
                ],
                'sort' => [

                ]
            ]
        );

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $this->filterTasks($params, $query);

        return $dataProvider;
    }

    /**
     * @param $params
     * @param \yii\db\Query $query
     * @throws \TaskForce\Exception\TaskForceException
     */
    public function filterTasks($params, Query $query): void
    {
        $list = [];
        // prepare categories array for search
        if (isset($params['CategoriesFilterForm']['categories'])) {
            foreach ($params['CategoriesFilterForm']['categories'] as $key => $item) {
                if ($item) {
                    $list[] = sprintf("'%s'", $key);
                }
            }
        }
        if (!empty($list)) {
            $categoryList = sprintf('c.id in (%s)', implode(",", $list));
            $query->andWhere($categoryList);
        }
        if (isset($params['withoutExecutor'])
            && $params['withoutExecutor'] === self::ENABLE_VALUE) {
            $query->andWhere('t.executor_id IS NULL');
        }
        if (isset($params['remoteWork'])
            && $params['remoteWork'] === self::ENABLE_VALUE) {
            $query->andWhere('t.lat IS NULL AND t.lng IS NULL');
        }
        if (isset($params['timeInterval'])
            && $params['timeInterval'] !== TasksFilterForm::FILTER_ALL_TIME) {
            $datetime = TasksFilterForm::timeBeforeInterval($params['timeInterval']);
            $query->andWhere("t.date_add > STR_TO_DATE('$datetime','%Y-%m-%d %H:%i:%s')");
        }
        if (isset($params['category'])) {
            $query->andWhere(['category_id' => $params['category']]);
        }
        $query->andFilterWhere(['LIKE', 't.name', $this->searchName]);
    }
}
