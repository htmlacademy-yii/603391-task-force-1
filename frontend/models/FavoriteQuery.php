<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Favorite]].
 *
 * @see Favorite
 */
class FavoriteQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Favorite[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Favorite|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
