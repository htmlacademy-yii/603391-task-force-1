<?php

use yii\db\Migration;

/**
 * Class m210410_020230_create_table_auth
 */
class m210410_020230_create_table_auth extends Migration
{
    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-auth-user_id-user-id','auth');
        $this->dropTable('auth');
    }
}