<?php
declare(strict_types=1);

namespace TaskForce\Helpers;

use TaskForce\Exception\TaskForceException;

class Utils
{
    public static function caseType($n): int
    {
        return ($n % 10 == 1 && $n % 100 != 11 ?
            0 :
            ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
    }

    public static function timeAfter(string $time): string
    {
        $casesMonth = array('месяц', 'месяца', 'месяцев');
        $casesDay = array('день', 'дня', 'дней');
        $casesHour = array('час', 'часа', 'часов');
        $casesMinute = array('минуту', 'минуты', 'минут');

        $now = new \DateTime();
        $sourceTime = new \DateTime($time);
        $dateInterval = $now->diff($sourceTime);

        if ($dateInterval->y >= 1) {
            return 'более года';
        }

        if ($dateInterval->m >= 1) {
            return sprintf('%2d %s', $dateInterval->m, $casesMonth[self::caseType($dateInterval->m)]);
        }

        if ($dateInterval->d >= 1) {
            return sprintf('%2d %s', $dateInterval->d, $casesDay[self::caseType($dateInterval->d)]);
        }

        if ($dateInterval->h >= 1) {
            return sprintf('%2d %s', $dateInterval->h, $casesHour[self::caseType($dateInterval->h)]);
        }

        if ($dateInterval->i > 1) {
            return sprintf('%2d %s', $dateInterval->i, $casesMinute[self::caseType($dateInterval->i)]);
        }
        return 'менее минуты';
    }

    public static function timeBeforeInterval(string $interval): ?string
    {
        $intervalDiff  = [
            'day'=>'-1 days',
            'week'=>'-7 days',
            'month'=>'-1 month',
        ];
        if (!isset($intervalDiff[$interval])) {
            throw new TaskForceException('Unknown interval name');
        }
        $date = new \DateTime($intervalDiff[$interval]);
        return  $date->format('Y-m-d h:i:s');
    }

}
