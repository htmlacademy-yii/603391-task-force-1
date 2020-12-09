<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Notification]].
 *
 * @see Notification
 */
class NotificationQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Notification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Notification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
