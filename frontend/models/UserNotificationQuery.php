<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UserNotification]].
 *
 * @see UserNotification
 */
class UserNotificationQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return UserNotification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserNotification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
