<?php

use frontend\models\City;
use frontend\models\Profile;
use frontend\models\User;
use yii\db\Migration;

/**
 * Class m201214_155941_update_profile_table_data
 */
class m201214_155941_update_profile_table_data extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     * @throws Throwable
     */
    public function safeUp()
    {
        // update user_id in profile
        $usersIds = User::find()->select('id')->asArray()->all();
        $profiles = Profile::find()->all();
        foreach ($profiles as $profile) {
            $userId = array_shift($usersIds);
            $profile->user_id = (int)$userId['id'];
            $profile->show = random_int(0,2000);
            $profile->show_it = (bool)random_int(0,1);
            $profile->show_only_executor = (bool)random_int(0,1);
            $profile->update();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_155941_update_profile_table_data cannot be reverted.\n";
        return false;
    }
}
