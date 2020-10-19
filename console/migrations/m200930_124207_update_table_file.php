<?php

use yii\db\Migration;

/**
 * Class m200930_124207_update_table_file
 */
class m200930_124207_update_table_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('file','created_at',$this->dateTime()->notNull()
            ->append('ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('file','created_at');
    }

}
