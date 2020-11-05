<?php

use yii\db\Migration;

/**
 * Class m201009_020104_update_table_profile
 */
class m201009_020104_update_table_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('profile', 'address', $this->string(255)->null()->after('id'));
        $this->alterColumn('profile', 'avatar', $this->string(255)->null()->after('messenger'));
        $this->alterColumn('profile', 'birthday', $this->date()->null()->after('address'));

        $this->alterColumn(
            'profile',
            'rate',
            $this->smallInteger(3)->unsigned()
                ->notNull()->defaultValue(0)->after('avatar')
        );
        $this->alterColumn(
            'profile',
            'show',
            $this->integer(11)->unsigned()
                ->notNull()->after('rate')->defaultValue('0')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('profile', 'address', $this->string()->notNull()->after('id'));
        $this->alterColumn('profile', 'birthday', $this->date()->notNull()->after('address'));

        $this->alterColumn(
            'profile',
            'rate',
            $this->smallInteger(3)->unsigned()
                ->null()->after('avatar')
        );
        $this->addColumn(
            'profile',
            'show',
            $this->integer(11)->notNull()->after('rate')->unsigned()
        );
    }

}
