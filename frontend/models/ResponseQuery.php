<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Response]].
 *
 * @see ResponseEntity
 */
class ResponseQuery extends ActiveQuery
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
