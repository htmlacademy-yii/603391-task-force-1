<?php

use yii\db\Migration;

/**
 * Class m201227_094432_update_chat_table
 */
class m201227_094432_update_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('FK_chat_user','chat');
        $this->dropForeignKey('FK_chat_user_2','chat');
        $this->dropColumn('chat','consumer_id');
        $this->dropColumn('chat','executor_id');
        $this->addColumn('chat','user_id', $this->integer(11)->unsigned()->NotNull()->after('id'));
        $this->addColumn('chat','is_new', $this->boolean()->unsigned()->NotNull()->after('message'));
        $this->addForeignKey(
            'FK_owner_user',
            'chat',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('chat','consumer_id', $this->integer(11)->unsigned()->NotNull()->after('id'));
        $this->addColumn('chat','executor_id', $this->integer(11)->unsigned()->NotNull()->after('id'));

        $this->addForeignKey(
            'FK_chat_user',
            'chat',
            'consumer_id',
            'user',
            'id'
        );
        $this->addForeignKey(
            'FK_chat_user2',
            'chat',
            'executor_id',
            'user',
            'id'
        );
    }
}
