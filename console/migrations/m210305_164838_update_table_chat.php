<?php

use yii\db\Migration;

/**
 * Class m210305_164838_update_table_chat
 */
class m210305_164838_update_table_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('chat','is_new', $this->boolean()->unsigned()->NotNull()->after('message')->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('chat','is_new', $this->boolean()->unsigned()->NotNull()->after('message'));
    }

}
