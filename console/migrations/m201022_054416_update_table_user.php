<?php

use yii\db\Migration;

/**
 * Class m201022_054416_update_table_user
 */
class m201022_054416_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','role',
                         "enum('customer','executor') NOT NULL DEFAULT 'customer' after `password`");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','role');
    }

}
