<?php

use yii\db\Migration;

/**
 * Class m200908_122132_update_table_task
 */
class m200908_122132_update_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'profile',
            'show',
            $this->integer(11)->notNull()->after('rate')->unsigned()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(
            'profile',
            'show'
        );
    }


}
