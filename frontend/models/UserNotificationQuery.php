<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[UserNotification]].
 *
 * @see UserNotification
 */
class UserNotificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
