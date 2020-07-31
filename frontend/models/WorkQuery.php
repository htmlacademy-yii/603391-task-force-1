<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Work]].
 *
 * @see Work
 */
class WorkQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
