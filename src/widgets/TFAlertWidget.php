<?php

namespace TaskForce\widgets;

use Yii;
use yii\base\Widget;

class TFAlertWidget extends Widget
{
    /**
     * Show Alert modal window without bootstrap css
     */

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $session->removeAllFlashes();
        parent::run();

        return $this->render('@widgets/tfAlertWidget/view', ['flashes' => $flashes]);
    }
}