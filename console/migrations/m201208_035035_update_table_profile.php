<?php

use yii\db\Migration;

/**
 * Class m201208_035035_update_table_profile
 */
class m201208_035035_update_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('profile','show_it', $this->boolean()->Null());
        $this->addColumn('profile','show_only_executor', $this->boolean()->Null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('profile','show_it');
        $this->dropColumn('profile','show_only_executor');
    }
}
