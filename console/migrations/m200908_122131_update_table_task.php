<?php

use yii\db\Migration;

/**
 * Class m200908_122131_update_table_task
 */
class m200908_122131_update_table_task extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            'task',
            'address',
            $this->string(255)->null()
        );

        $this->alterColumn(
            'task',
            'lat',
            $this->decimal(10,8)->null()
        );

        $this->alterColumn(
            'task',
            'lng',
            $this->decimal(11, 8)->null()
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(
            'task',
            'address',
            $this->string(255)->notNull()
        );

        $this->alterColumn(
            'task',
            'lat',
            $this->decimal(10,8)->notNull()
        );

        $this->alterColumn(
            'task',
            'lng',
            $this->decimal(11, 8)->notNull()
        );
    }


}
