<?php

use yii\db\Migration;

/**
 * Class m201009_020105_update
 */
class m201009_020105_update extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE `task` DROP FOREIGN KEY `FK_task_status`;
                DROP TABLE `status`;
                ALTER TABLE `task` DROP COLUMN `status_id`;
                ALTER TABLE `task` ADD COLUMN `status`
                    ENUM(\'New\',\'Cancel\',\'In work\',\'Complete\',\'Failed\')
                    NOT NULL DEFAULT \'New\' AFTER `address`;';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201009_020105_update cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201009_020105_update cannot be reverted.\n";

        return false;
    }
    */
}
