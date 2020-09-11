<?php

use yii\db\Migration;

/**
 * Class m200908_115520_Update_table_spercialization
 */
class m200908_115520_update_table_spercialization extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropTable('favorite');
        $sql='CREATE TABLE IF NOT EXISTS `favorite` (
                  `user_id` int(11) unsigned NOT NULL,
                  `favorite_id` int(11) unsigned NOT NULL,
                  PRIMARY KEY (`user_id`,`favorite_id`),
                  KEY `FK_favorite_customer` (`favorite_id`),
                  CONSTRAINT `FK_favorite_customer` FOREIGN KEY (`favorite_id`) REFERENCES `user` (`id`),
                  CONSTRAINT `FK_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_115520_Update_table_spercialization cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200908_115520_Update_table_spercialization cannot be reverted.\n";

        return false;
    }
    */
}
