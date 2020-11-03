<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Profile]].
 *
 * @see Profile
 */
class ProfileQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
