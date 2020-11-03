<?php

use yii\db\Migration;

/**
 * Class m201103_020516_update_table_profile
 */
class m201103_020516_update_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('profile','avatar', $this->string(255)->Null()->defaultValue('no-avatar.jpg'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('profile','avatar', $this->string(255)->null());
    }

}
