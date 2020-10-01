<?php

use yii\db\Migration;

/**
 * Class m200930_124207_update_table_task
 */
class m200930_124207_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE `task` MODIFY `expire` TIMESTAMP DEFAULT NULL;
                ALTER TABLE `file` ADD `created_at` `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ;';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200930_124207_update_table_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200930_124207_update_table_task cannot be reverted.\n";

        return false;
    }
    */
}
