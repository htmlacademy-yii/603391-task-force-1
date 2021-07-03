<?php

namespace frontend\models;

use yii\web\NotFoundHttpException;

trait ExceptionOnFindFail
{
    /**
     * @param $param
     * @param null $exceptionMessage
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findOrFail($param, $exceptionMessage = null)
    {
        if (($res = static::findOne($param)) === null) {
            $defaultException = static::class . " not found";
            throw new NotFoundHttpException($exceptionMessage ?? $defaultException);
        }

        return $res;
    }
}
