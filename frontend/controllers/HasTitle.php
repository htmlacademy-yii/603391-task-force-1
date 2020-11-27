<?php

namespace frontend\controllers;

use TaskForce\Constant\Page;
use Yii;

trait HasTitle
{
    public function getTitle()
    {
        Yii::$app->view->title = Yii::$app->params['AppName'] . ' - ' . Page::ROUTE_TO_PAGE_NAME
            [Yii::$app->controller->id . '/' . Yii::$app->controller->action->id];
    }
}