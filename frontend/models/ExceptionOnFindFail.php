<?php

namespace frontend\models;

use yii\web\NotFoundHttpException;

trait ExceptionOnFindFail
{
    public static function findOrFail($param, $exceptionMessage = null)
    {
        if (($res = static::findOne($param)) === null) {
            $defaultException = static::class . " not found";
            throw new NotFoundHttpException($exceptionMessage ?? $defaultException);
        }

        return $res;
    }
}
