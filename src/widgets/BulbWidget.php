<?php

namespace TaskForce\widgets;

use yii\base\Widget;

class BulbWidget extends Widget
{
    public function run(): ?string
    {
        parent::run();

        return $this->render('@widgets/bulb/view');
    }
}
