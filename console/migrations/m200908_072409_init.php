<?php

use yii\db\Migration;

/**
 * Class m200908_072409_init
 */
class m200908_072409_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents( __DIR__ . '/data/scheme.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_072409_init cannot be reverted.\n";

        return false;
    }




}
