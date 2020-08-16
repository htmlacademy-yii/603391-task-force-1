<?php


namespace TaskForce\Helpers;

class Utils
{
    public static function plural_type($n)
    {
        return ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
    }


    public static function timeAfter(string $time): string
    {
        $_plural_months = array('месяц', 'месяца', 'месяцев');
        $_plural_days = array('день', 'дня', 'дней');
        $_plural_hours = array('час', 'часаа', 'часов');
        $_plural_minutes = array('минуту', 'минуты', 'минут');

        $now = new \DateTime();
        $sourceTime = new \DateTime($time);
        $dateInterval = $now->diff($sourceTime);

        if ($dateInterval->y >= 1) {
            return 'более года';
        }

        if ($dateInterval->m >= 1) {
            return sprintf('%2d ', $dateInterval->m) . $_plural_months[self::plural_type($dateInterval->m)];
        }

        if ($dateInterval->d >= 1) {
            return sprintf('%2d ', $dateInterval->d) . $_plural_days[self::plural_type($dateInterval->d)];
        }

        if ($dateInterval->h >= 1) {
            return sprintf('%2d ', $dateInterval->h) . $_plural_hours[self::plural_type($dateInterval->h)];
        }

        if ($dateInterval->i > 1) {
            return sprintf('%2d ', $dateInterval->i) . $_plural_minutes[self::plural_type($dateInterval->i)];
        }
        return 'менее минуты';
    }
}
