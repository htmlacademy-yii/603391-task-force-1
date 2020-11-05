<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Specialization]].
 *
 * @see Specialization
 */
class SpecializationQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Specialization[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Specialization|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
