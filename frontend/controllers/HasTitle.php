<?php

namespace frontend\controllers;

trait HasTitle
{
    public function getTitle (string $route): ?string
    {
        var_dump(Yii::$app->controller->route);

        return [
            ''=>'',
            ''=>'',
            ''=>'',
            ''=>'',
            ''=>'',
            ''=>'',
            ''=>'',
            ''=>'',
        ][$route];


    }
}