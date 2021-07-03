<?php

namespace frontend\components;

use DateTime;
use Exception;
use frontend\models\Auth;
use frontend\models\City;
use frontend\models\Profile;
use frontend\models\User;
use TaskForce\Constant\UserRole;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    private const MESSAGE = "Пользователь с тем же адресом электронной почты, что и в аккаунте {client},"
    . " уже существует, но не связан с ним." .
    "Сначала войдите, используя электронную почту, чтобы привязать аккаунт.";
    private const FIRST_ID = 1;

    /**
     * AuthHandler constructor.
     * @param ClientInterface $client
     */
    public function __construct(
        private ClientInterface $client
    ) {
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $city = ArrayHelper::getValue($attributes, 'city.title');
        $fullName = ArrayHelper::getValue($attributes, 'first_name')
            . ' ' . ArrayHelper::getValue($attributes, 'last_name');

        $auth = Auth::findAuthByClient(id: $id, clientId: $this->client->getId());

        if (Yii::$app->user->isGuest) {
            if ($auth) {
                $this->login($auth);
            } else {
                $auth = $this->register($email, $city, $fullName, $id);
            }
        }
        if (!Yii::$app->user->isGuest) {
            if (!$auth) {
                $this->addAuthProvider($id);
            } else {
                Yii::$app->getSession()->setFlash(
                    'error',
                    Yii::t(
                        'app',
                        'Невозможно привязать аккаунт {client}. Его использует другой пользователь.',
                        ['client' => $this->client->getTitle()]
                    ),
                );
            }
        }
    }

    /**
     * @param string $authProviderId
     */
    public function addAuthProvider(string $authProviderId): void
    {
        $auth = $this->createAuth(Yii::$app->user->id, $authProviderId);
        if ($auth->save()) {
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t(
                    'app',
                    'Привязанный аккаунт {client}.',
                    [
                        'client' => $this->client->getTitle()
                    ]
                ),
            );
        } else {
            Yii::$app->getSession()->setFlash(
                'error',
                Yii::t(
                    'app',
                    'Невозможно привязать аккаунт {client}: {errors}',
                    [
                        'client' => $this->client->getTitle(),
                        'errors' => json_encode($auth->getErrors()),
                    ]
                ),
            );
        }
    }

    /**
     * @param array|Auth $auth
     */
    public function login(array|Auth $auth): void
    {
        $user = $auth->user;
        Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
        $user->date_login = new DateTime('now');
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function register(string $email, string $city, string $fullName, string $id): ?Auth
    {
        if ($email !== null && User::find()->where(['email' => $email])->exists()) {
            Yii::$app->getSession()->setFlash(
                'error',
                Yii::t('app', self::MESSAGE, ['client' => $this->client->getTitle()]),
            );
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = $this->createUser($city, $email, $fullName);
                $this->createProfile($user);
                $transaction->commit();
            } catch (Exception) {
                $transaction->rollBack();
            }
            $transaction = User::getDb()->beginTransaction();

            if (isset($user->id)) {
                $auth = $this->createAuth($user->id, $id);

                if ($auth->save()) {
                    $transaction->commit();
                    Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                } else {
                    Yii::$app->getSession()->setFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Не удалось сохранить {client} аккаунт: {errors}',
                            [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($auth->getErrors()),
                            ]
                        ),
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash(
                    'error',
                    Yii::t(
                        'app',
                        'Невозможно сохранить пользователя: {errors}',
                        [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($user->getErrors()),
                        ]
                    ),
                );
            }
        }

        return $auth ?? null;
    }

    /**
     * @param string $city
     * @param string $email
     * @param string $fullName
     * @return User
     * @throws \yii\base\Exception
     */
    private function createUser(string $city, string $email, string $fullName): User
    {
        $user = new User();
        $user->city_id = City::findIdByName($city) || self::FIRST_ID;
        $user->email = $email;
        $user->name = strip_tags($fullName);
        $password = Yii::$app->security->generateRandomString(9);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->role = UserRole::CUSTOMER;
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->generatePasswordResetToken();
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     */
    private function createProfile(User $user): void
    {
        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->save();
    }

    /**
     * @param string $user_id
     * @param string $id
     * @return Auth
     */
    private function createAuth(string $user_id, string $id): Auth
    {
        return new Auth(
            [
                'user_id' => $user_id,
                'source' => $this->client->getId(),
                'source_id' => $id,
            ]
        );
    }
}