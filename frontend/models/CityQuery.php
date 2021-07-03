<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[City]].
 *
 * @see City
 */
class CityQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
