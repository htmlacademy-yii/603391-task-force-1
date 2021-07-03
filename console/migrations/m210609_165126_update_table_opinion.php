<?php

use yii\db\Migration;

/**
 * Class m210609_165126_update_table_opinion
 */
class m210609_165126_update_table_opinion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('opinion', ['task_id' => '1']);
        $this->addForeignKey('fk-opinion-task_id-task-id', 'opinion', 'task_id', 'task', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-opinion-task_id-task-id','opinion');
        $this->update('opinion', ['task_id' => '0']);
    }
}
