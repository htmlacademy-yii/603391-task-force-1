<?php

namespace frontend\models\forms;

use Exception;
use frontend\models\City;
use frontend\models\Profile;
use frontend\models\User;
use TaskForce\Exception\TaskForceException;
use TaskForce\Constant\UserRole;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public const NOT_FILLED = 'Поле не заполнено.';

    public string $email = '';
    public string $username = '';
    public string $cityId = '';
    public string $password = '';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => self::NOT_FILLED],
            ['email', 'email', 'message' => 'Неверный адрес.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Данный email уже занят.'],
            ['username', 'trim'],
            ['username', 'required', 'message' => self::NOT_FILLED],
            ['cityId', 'required', 'message' => self::NOT_FILLED],
            [
                'cityId',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Введен неверный город'
            ],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Пароль должен быть не менее 8 символов.'],
            ['password', 'required', 'message' => self::NOT_FILLED],
        ];
    }

    /**
     *  Registration users by form data
     * @return bool
     * @throws TaskForceException
     */

    public function register(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->name = $this->username;
            $user->city_id = $this->cityId;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->role = UserRole::CUSTOMER;
            $user->save();

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();

            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw new TaskForceException("Ошибка регистрации пользователя. " . $e->getMessage());
        }

        return true;
    }
}
