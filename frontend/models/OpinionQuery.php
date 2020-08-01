<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Opinion]].
 *
 * @see Opinion
 */
class OpinionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Opinion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Opinion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
