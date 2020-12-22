<?php

use yii\db\Migration;

/**
 * Class m201117_085908_insert_data_table_notification
 */
class m201117_085908_insert_data_table_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('notification', ['in', 'id', [1, 2, 3]]);

        $this->batchInsert(
            'notification',
            ['id', 'name', 'name_rus'],
            [
                [1, 'New message', 'Новое сообщение'],
                [2, 'Task actions', 'Действия по заданию'],
                [3, 'New review', 'Новый отзыв'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('notification', ['in', 'id', [1, 2, 3]]);
        $this->batchInsert(
            'notification',
            ['id', 'name'],
            [
                [1, 'New message'],
                [2, 'Task actions'],
                [3, 'New review'],
            ]
        );
    }
}
