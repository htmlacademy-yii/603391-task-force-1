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
                NOT NULL DEFAULT \'New\' AFTER `address`;

                ALTER TABLE `profile` CHANGE COLUMN `address` `address` VARCHAR(255) NULL AFTER `id`,
                CHANGE COLUMN `birthday` `birthday` DATE NULL AFTER `address`;

                ALTER TABLE `profile`
	            CHANGE COLUMN `rate` `rate` TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\' AFTER `avatar`,
	            CHANGE COLUMN `show` `show` INT(11) UNSIGNED NOT NULL DEFAULT \'0\' AFTER `rate`;

                ALTER TABLE `profile`
	            CHANGE COLUMN `avatar` `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `messenger`;';
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
