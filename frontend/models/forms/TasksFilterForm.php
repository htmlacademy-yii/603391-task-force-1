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
    public const FILTER_ALL_TIME = 'all'; // для тестирования

    public $withoutExecutor = false;
    public $remoteWork = false;
    public $timeInterval = false;
    public $searchName = false;

    public static function getIntervalList()
    {
        return [
            self::FILTER_DAY => 'За день',
            self::FILTER_WEEK => 'За неделю',
            self::FILTER_MONTH => 'За месяц',
            self::FILTER_ALL_TIME => 'За все время',
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
