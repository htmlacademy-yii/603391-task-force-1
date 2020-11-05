<?php

use yii\db\Migration;

/**
 * Class m200924_050950_update_tables_user
 */
class m200924_050950_update_tables_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','city_id', $this->integer(11)->unsigned()->notNull()
        ->after('id'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','city_id');
    }


}
