<?php

namespace frontend\models\forms;

use frontend\models\City;
use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\User;
use TaskForce\Exception\TaskForceException;
use TaskForce\Task;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $email = '';
    public $username = '';
    public $cityId = '';
    public $password = '';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => 'Поле не заполнено.'],
            ['email', 'email', 'message' => 'Неверный адрес.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Данный email уже занят.'],
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Поле не заполнено.'],
            ['cityId', 'required', 'message' => 'Поле не заполнено.'],
            [
                'cityId',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Введен неверный город'
            ],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Пароль должен быть не менее 8 символов.'],
            ['password', 'required', 'message' => 'Поле не заполнено.'],
        ];
    }

    /**
     *  Registration users by form data
     * @return bool
     * @throws \yii\base\Exception
     * @throws TaskForceException
     */

    public function register(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->name = $this->username;
            $user->city_id = $this->cityId;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->save();
            $userId = $user->id;


            $profile = new Profile();
            $profile->user_id = $userId;
            $profile->role = Task::ROLE_CUSTOMER;

            $profile->save();

            $transaction->commit();
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new TaskForceException("Error registration user with ID #$userId. ". $e->getMessage());
            return false;
        }


        return true;
    }
}
