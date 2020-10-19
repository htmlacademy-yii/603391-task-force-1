<?php

use yii\db\Migration;

/**
 * Class m200930_124208_update_table_task
 */
class m200930_124208_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('task','expire',$this->dateTime()->null()
            ->append('ON UPDATE CURRENT_TIMESTAMP')
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->alterColumn('task','expire',$this->dateTime()->notNull()
        );
    }

}
