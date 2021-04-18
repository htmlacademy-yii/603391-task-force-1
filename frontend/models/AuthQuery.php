<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Auth]].
 *
 * @see \frontend\models\Auth
 */
class AuthQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return \frontend\models\Auth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \frontend\models\Auth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}