<?php


namespace frontend\models\forms;


use DateTime;
use Exception;
use TaskForce\Exception\TaskForceException;
use yii\base\Model;

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

    public bool $withoutExecutor = false;
    public bool $remoteWork = false;
    public bool $timeInterval = false;
    public bool $searchName = false;

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
     * @return array|string[]
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
    public function attributeLabels(): array
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
    public function rules(): array
    {
        return [
            [['withoutExecutor', 'remoteWork', 'timeInterval', 'searchName'], 'safe'],
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


}
