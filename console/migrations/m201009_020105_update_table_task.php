<?php

use yii\db\Migration;

/**
 * Class m201009_020105_update_table_task
 */
class m201009_020105_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('FK_task_status', 'task');
        $this->dropTable('status');
        $this->dropColumn('task', 'status_id');

        $sql = 'ALTER TABLE `task` ADD COLUMN `status`
                ENUM(\'New\',\'Cancel\',\'In work\',\'Complete\',\'Failed\')
                NOT NULL DEFAULT \'New\' AFTER `address`;';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable(
            'status',
            array(
                'id' => $this->primaryKey()->notNull()->unsigned(),
                'name' => $this->string(50)->notNull(),
            )
        );
        $this->addColumn('task', 'status_id', $this->integer(11)->unsigned()->notNull());
        $this->dropColumn('task', 'status');
        $this->addForeignKey('FK_task_status', 'task', 'status_id', 'status', 'id');
    }

}
