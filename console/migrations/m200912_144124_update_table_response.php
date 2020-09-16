<?php

use yii\db\Migration;

/**
 * Class m200912_144124_update_table_response
 */
class m200912_144124_update_table_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE `response` DROP COLUMN `rate`;
              ALTER TABLE `response` ADD COLUMN `user_id`  INT(11) UNSIGNED NOT NULL AFTER `status`;
              ALTER TABLE `opinion` ADD COLUMN `task_id`  INT(11) UNSIGNED NOT NULL AFTER `created_at`;
              ALTER TABLE `profile` ADD COLUMN `show`  INT(11) UNSIGNED NOT NULL AFTER `rate`;';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200912_144124_update_table_response cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200912_144124_update_table_response cannot be reverted.\n";

        return false;
    }
    */
}
