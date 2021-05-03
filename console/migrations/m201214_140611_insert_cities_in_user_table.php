    <?php

use frontend\models\City;
use frontend\models\User;
use TaskForce\Constant\UserRole;
use yii\db\Migration;

/**
 * Class m201214_140611_insert_cities_in_user_table
 */
class m201214_140611_insert_cities_in_user_table extends Migration
{
    const DEFAULT_PASSWORD_123123123 = '$2y$13$dw8i4nQyKCwJ0aO0.zl/Z.2dzViRrPNgP0Wl2vO139RmcsFvaNw8.';

    /**
     * {@inheritdoc}
     * @throws Exception|Throwable
     */
    public function safeUp()
    {
        // insert random cities id from Санкт-Питербург, Москва, Новосибирск & Random Role.
        $listIds = City::find()->select('id')->where(['city'=>['Санкт-Петербург','Москва', 'Новосибирск']])->asArray()->all();
        $usersIds = User::find()->all();
        foreach ($usersIds as $user) {
            $randomCity =  $listIds [random_int(0,2)];
            $user->city_id = $randomCity;
            $user->role = [UserRole::CUSTOMER, UserRole::EXECUTOR] [random_int(0, 1)];
            $user->password = self::DEFAULT_PASSWORD_123123123;
            $user->generatePasswordResetToken();
            $user->update();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $usersIds = User::find()->all();
        foreach ($usersIds as $user) {
            $user->city_id = 0;
            $user->update();
        }
    }
}
