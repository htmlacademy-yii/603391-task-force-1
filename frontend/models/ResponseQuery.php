<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Response]].
 *
 * @see Response
 */
class ResponseQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Response[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Response|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
