<?php

use yii\db\Migration;

/**
 * Class m210609_165545_update_table_response
 */
class m210609_165545_update_table_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('response', ['user_id' => '1']);
        $this->addForeignKey('fk-response-user_id-user-id', 'response', 'user_id', 'user', 'id');
        // gafgadfgsdfgds
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-response-user_id-user-id','response');
        $this->update('response', ['task_id' => '0']);
    }
}
