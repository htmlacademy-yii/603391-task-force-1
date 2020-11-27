<?php

namespace frontend\widgets;

use yii\base\Widget;

class StarRatingWidget extends Widget
{
    public int $rate;

    /**
     * @return string|null
     */
    public function run(): ?string
    {
        $html = '';
        if (!empty($this->rate)) {
            $html .= str_repeat('<span></span>', $this->rate);
            $html .= str_repeat('<span class="star-disabled"></span>', 5 - $this->rate);
            $html .= "<b>$this->rate</b>";
        }

        return $html;
    }
}