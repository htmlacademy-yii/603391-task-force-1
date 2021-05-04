<?php

use yii\db\Migration;

/**
 * Class m200924_050951_update_tables_user_profile
 */
class m200924_050951_update_tables_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('FK_profile_city', 'profile');
        $this->dropColumn('profile','city_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('profile','city_id', $this->integer(11)->unsigned()->notNull());
        $this->addForeignKey('FK_profile_city','profile', 'city_id','city', 'id');
    }
}
