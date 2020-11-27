<?php

namespace frontend\widgets;

use yii\base\Widget;

class StarRating extends Widget
{
    public int $rate = 0;

    /**
     * TaskForce StarRating widget. It shows stars + value rating by number in rate value.
     * Without own css. Uses the project's css.
     *
     * @return string|null
     */
    public function run(): ?string
    {
        $html = str_repeat('<span></span>', $this->rate);
        $html .= str_repeat('<span class="star-disabled"></span>', 5 - $this->rate);
        $html .= "<b>$this->rate</b>";

        return $html;
    }
}