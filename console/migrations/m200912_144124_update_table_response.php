<?php

use yii\db\Migration;

/**
 * Class m200912_144124_update_table_response
 */
class m200912_144124_update_table_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('response', 'rate');
        $this->addColumn('response', 'user_id', $this->integer(11)->unsigned()->notNull()
            ->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('response', 'rate', $this->smallInteger(3)->unsigned()->notNull()
            ->after('status'));
        $this->dropColumn('response', 'user_id');
    }

}
