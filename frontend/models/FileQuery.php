<?php

namespace frontend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[File]].
 *
 * @see File
 */
class FileQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return File[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return File|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
