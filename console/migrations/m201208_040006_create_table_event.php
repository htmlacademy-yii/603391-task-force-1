<?php

use yii\db\Migration;

/**
 * Class m201208_040006_create_table_event
 */
class m201208_040006_create_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'event',
            [
                'id' => $this->primaryKey(),
                'date' => $this->dateTime()->NotNull(),
                'user_id' => $this->integer()->unsigned()->NotNull(),
                'notification_id' => $this->integer()->unsigned()->NotNull(),
                'task_id' => $this->integer()->unsigned()->NotNull(),
                'info' => $this->string()->Null(),
                'viewed' => $this->boolean()->Null()
            ]
        );

        $this->createIndex('idx_event_user', 'event', 'user_id');

        $this->addForeignKey(
            'FK_event_user',
            'event',
            'user_id',
            'user',
            'id'
        );
        $this->addForeignKey(
            'FK_event_notification',
            'event',
            'notification_id',
            'notification',
            'id'
        );
        $this->addForeignKey(
            'FK_event_task',
            'event',
            'task_id',
            'task',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('event');
    }
}
