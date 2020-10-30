<?php

use yii\db\Migration;

/**
 * Class m200924_050951_update_tables_user_profile
 */
class m200924_050951_update_tables_user_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE `user` ADD COLUMN `city_id`  INT(11) UNSIGNED NOT NULL AFTER `id`;
                ALTER TABLE `profile`  DROP FOREIGN KEY `FK_profile_city`;
                ALTER TABLE `profile` DROP  COLUMN `city_id`;
              ';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200924_050951_update_tables_user_profile cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200924_050951_update_tables_user_profile cannot be reverted.\n";

        return false;
    }
    */
}
