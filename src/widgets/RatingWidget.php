<?php

namespace TaskForce\widgets;

use TaskForce\widgets\rating\RatingWidgetBundle;
use yii\base\Widget;

/**
 * TaskForce StarRating widget. It shows stars + value or rating by number in rate value.
 * Without own css. Uses the project's css.
 * @property int rate
 * @property int type value 1 - five stars, 2 - one color star
 *
 *
 * @property-read null|string $starsContent
 * @property-read string $colorStarContent
 */
class RatingWidget extends Widget
{
    const STARS_TYPE = 1;
    const COLOR_NUMS_TYPE = 2;
    const RATE_RANGE = [1, 2, 3, 4, 5];
    const TYPES = [self::COLOR_NUMS_TYPE, self::STARS_TYPE];

    public int $rate = 0;
    public int $type = self::STARS_TYPE;

    /**
     * @return bool
     */
    private function validate()
    {
        if (!in_array($this->rate, self::RATE_RANGE) && !in_array($this->type, self::TYPES)) {
            return false;
        }

        return true;
    }

    /**
     * TaskForce StarRating widget. It shows stars + value or rating by number in rate value.
     * Without own css. Uses the project's css.
     *
     * @return string|null
     */
    public function run()
    {
        parent::run();

        if (!$this->validate()) {
            return null;
        }

        return match($this->type) {
            self::COLOR_NUMS_TYPE => $this->getColorStarContent(),
            self::STARS_TYPE => $this->getStarsContent(),
            default => ''
            };
    }

    /**
     *  @inheritdoc
     *  Register css to widget
     */
    public function init()
    {
        RatingWidgetBundle::register($this->getView());
        parent::init();
    }

    /**
     * Show 5 colored stars by rate
     * @return string
     */
    private function getStarsContent(): string
    {
        $content = str_repeat('<span></span>', $this->rate);
        $content .= str_repeat('<span class="star-disabled"></span>', 5 - $this->rate);

        return $content . "<b>$this->rate</b>";
    }

    /**
     * @return string
     */
    private function getColorStarContent(): string
    {
        $template = '<div class="card__review-rate"><p class="%s big-rate">%s<span></span></p></div>';
        $class = ($this->rate >= 4) ? 'five-rate' : 'three-rate';

        return sprintf($template, $class, $this->rate);
    }
}