<?php

namespace TaskForce\Helpers;

use DateTime;
use Exception;
use Yii;

class Declination
{
    public function __construct(
        public string $firstForm = '',
        public string $secondForm = '',
        public string $thirdForm = ''
    ) {
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
     * @throws Exception
     */
    public static function getTimeAfter(?string $time): string
    {
        return  Yii::$app->formatter->asRelativeTime(new DateTime($time));
    }
}
