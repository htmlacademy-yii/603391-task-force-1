<?php


namespace frontend\models;


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
