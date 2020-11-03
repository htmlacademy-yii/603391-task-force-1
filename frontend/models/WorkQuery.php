<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Work]].
 *
 * @see Work
 */
class WorkQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Work[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Work|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
