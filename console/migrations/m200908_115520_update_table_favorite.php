<?php

use yii\db\Migration;

/**
 * Class m200908_115520_Update_table_favorite
 */
class m200908_115520_update_table_favorite extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'FK_favorite_files',
            'favorite'
        );

        $this->addForeignKey(
            'FK_favorite_customer',
            'favorite',
            'favorite_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'FK_favorite_customer',
            'favorite'
        );

        $this->addForeignKey(
            'FK_favorite_files',
            'favorite',
            'favorite_id',
            'file',
            'id'
        );

    }


}
