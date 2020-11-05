<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Chat]].
 *
 * @see Chat
 */
class ChatQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Chat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Chat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
