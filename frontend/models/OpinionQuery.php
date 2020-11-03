<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Opinion]].
 *
 * @see Opinion
 */
class OpinionQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Opinion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Opinion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
