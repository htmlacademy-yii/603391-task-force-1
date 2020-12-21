<?php

namespace frontend\controllers;

use TaskForce\Constant\Page;
use Yii;

trait HasTitle
{
    public function getTitle()
    {
        $pageName = Page::ROUTE_TO_TITLE[Yii::$app->controller->id . '/' . Yii::$app->controller->action->id] ?? '';
        if ($pageName) {
            $pageName = ' - ' . $pageName;
        }

        return Yii::$app->params['AppName'] . $pageName;
    }
}
