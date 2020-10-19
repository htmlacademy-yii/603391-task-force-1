<?php

use yii\db\Migration;

/**
 * Class m200912_144125_update_table_opinion
 */
class m200912_144125_update_table_opinion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('opinion', 'task_id', $this->integer(11)->unsigned()->notNull()
        ->after('created_at'));
     }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('opinion', 'task_id');
    }

}
