<?php

use yii\db\Migration;

/**
 * Class m201117_082841_update_table_notification
 */
class m201117_082841_update_table_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notification','name_rus', $this->string(64)->unsigned()->notNull()
            ->after('name'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('notification','name_rus');
    }
}
