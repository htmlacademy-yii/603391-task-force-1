<?php

use yii\db\Migration;

/**
 * Class m201022_054407_update_table_profile
 */
class m201022_054407_update_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('profile','role');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('profile','role',
                         "enum('customer','executor') NOT NULL DEFAULT 'customer' after `rate`");
    }
}
