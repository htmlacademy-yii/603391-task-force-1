<?php

use yii\db\Migration;

/**
 * Class m201119_081158_updae_table_work
 */
class m201119_081158_updae_table_work extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('work','generated_name',
                         $this->string(512)->notNull()->after('filename'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('work','generated_name');
    }
}
