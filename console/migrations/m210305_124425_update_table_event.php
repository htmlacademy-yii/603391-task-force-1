<?php

use yii\db\Migration;

/**
 * Class m210305_124425_update_table_event
 */
class m210305_124425_update_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('event','date', $this->dateTime()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('event','date', $this->dateTime()->NotNull());
    }
}
