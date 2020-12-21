<?php

namespace TaskForce\widgets;

use TaskForce\widgets\dropzone\DropzoneWidgetBundle;
use yii\base\Widget;

class DropZoneWidget extends Widget
{
     /**
     *
     * @return string|null
     */
    public function run(): ?string
    {
        parent::run();
        return $this->render('@widgets/dropzone/view' );
    }

    /**
     *  Register css to widget
     */
    public function init()
    {
        DropzoneWidgetBundle::register($this->getView());
        parent::init();
    }
}
