<?php

use yii\db\Migration;

/**
 * Class m201110_041613_update_table_task
 */
class m201110_041613_update_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'city_id', $this->integer(11)->unsigned()->Null()->after('address'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'city');
    }
}
