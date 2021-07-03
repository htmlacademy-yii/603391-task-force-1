<?php

use yii\db\Migration;

/**
 * Class m210609_165512_update_table_specialization
 */
class m210609_165512_update_table_specialization extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-specialization-profile_id-profile-id', 'specialization', 'profile_id', 'profile', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-specialization-profile_id-profile-id','specialization');
    }
}
