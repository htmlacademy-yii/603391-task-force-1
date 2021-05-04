<?php

use yii\db\Migration;

/**
 * Class m201210_020319_update_table_user
 */
class m201210_020319_update_table_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user','auth_key', $this->string()->notNull());
        $this->addColumn('user','password_reset_token', $this->string()->notNull());
        $this->addColumn('user','status', $this->smallInteger()->notNull()->defaultValue(10));
    }

    public function safeDown()
    {
        $this->dropColumn('user', 'auth_key');
        $this->dropColumn('user', 'password_reset_token');
        $this->dropColumn('user', 'status');
    }
}