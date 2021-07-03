<?php

use yii\db\Migration;

/**
 * Class m210609_171413_update_table_user
 */
class m210609_171413_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-user-city_id-city-id', 'user', 'city_id', 'city', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user-city_id-city-id','user');
    }

}
