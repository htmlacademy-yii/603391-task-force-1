<?php

use yii\db\Migration;

/**
 * Class m201026_072151_update_table_opinion
 */
class m201026_072151_update_table_opinion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('opinion','done',
                         $this->boolean()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('opinion','done');
    }

}
