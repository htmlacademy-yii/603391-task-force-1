<?php

use yii\db\Migration;

/**
 * Class m200908_105359_load_fake_data
 */
class m200908_105359_load_fake_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents( __DIR__ . '/data/status.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/categories.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/cities.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/users.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/opinions.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/profiles.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/tasks.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/replies.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/specializations.sql'));
        $this->execute(file_get_contents( __DIR__ . '/data/notifications.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200908_105359_load_fake_data cannot be reverted.\n";

        return false;
    }

}
