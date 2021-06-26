<?php

use yii\db\Migration;

/**
 * Class m210609_164715_update_table_task
 */
class m210609_164715_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addForeignKey('fk-task-city_id-city-id', 'task', 'city_id', 'city', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-city_id-city-id','task');
    }
}
