<?php

namespace TaskForce\Helpers;

use DateTime;
use Exception;
use TaskForce\Exception\TaskForceException;

class Declination
{
    public string $firstForm = '';
    public string $secondForm = '';
    public string $thirdForm = '';

    public function __construct(string $first, string $second, string $third)
    {
        $this->firstForm = $first;
        $this->secondForm = $second;
        $this->thirdForm = $third;
    }

    private function getWordsList(): array
    {
        return array($this->firstForm, $this->secondForm, $this->thirdForm);
    }

    /**
     * @param $n
     * @return int
     */
    public static function caseType(?int $n): int
    {
        $form = ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);
        return ($n % 10 == 1 && $n % 100 != 11) ? 0 : $form;
    }

    /**
     * @param int|null $value
     * @return string
     */
    public function getWord(?int $value = 0): string
    {
        $caseWords = $this->getWordsList();
        return ' ' . $caseWords[self::caseType($value)];
    }

    /**
     * @param string|null $time
     * @return string
     * @throws TaskForceException
     */
    public static function getTimeAfter(?string $time): string
    {
        $now = new DateTime();

        try {
            $sourceTime = new DateTime($time);
        } catch (Exception $e) {
            throw new TaskForceException($e);
        }
        $dateInterval = $now->diff($sourceTime);

        $units = [
            [$dateInterval->y, ['год', 'года', 'лет']],
            [$dateInterval->m, ['месяц', 'месяца', 'месяцев']],
            [$dateInterval->d, ['день', 'дня', 'дней']],
            [$dateInterval->h, ['час', 'часа', 'часов']],
            [$dateInterval->i, ['минуту', 'минуты', 'минут']]
        ];

        foreach ($units as $unit) {
            if ($unit[0] >= 1) {
                return sprintf('%2d %s', $unit[0], $unit[1][self::caseType($unit[0])]);
            }
        }
        return 'менее минуты';
    }
}
