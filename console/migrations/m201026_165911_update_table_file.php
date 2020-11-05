<?php

use yii\db\Migration;

/**
 * Class m201026_165911_update_table_file
 */
class m201026_165911_update_table_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('file','generated_name',
                         $this->string(512)->notNull()->after('filename'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('file','generated_name');
    }

}
