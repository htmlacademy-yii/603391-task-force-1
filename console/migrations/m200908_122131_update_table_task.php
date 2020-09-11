<?php

use yii\db\Migration;

/**
 * Class m200908_122131_update_table_task
 */
class m200908_122131_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql='ALTER TABLE `task` MODIFY `address` varchar(255) DEFAULT NULL;
         ALTER TABLE `task` MODIFY `lat` decimal(10,8) DEFAULT NULL;
        ALTER TABLE `task` MODIFY `lng` decimal(11,8) DEFAULT NULL;
        UPDATE `profile` SET avatar = \'man-glasses.jpg\' WHERE avatar = \'no-avatar.jpg\'';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_122131_update_table_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200908_122131_update_table_task cannot be reverted.\n";

        return false;
    }
    */
}
