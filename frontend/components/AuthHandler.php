<?php

namespace frontend\components;

use DateTime;
use Exception;
use frontend\models\Auth;
use frontend\models\City;
use frontend\models\Profile;
use frontend\models\User;
use TaskForce\Constant\UserRole;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;


/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    const MESSAGE = "Пользователь с тем же адресом электронной почты, что и в аккаунте {client},"
    . " уже существует, но не связан с ним." .
    "Сначала войдите, используя электронную почту, чтобы привязать аккаунт.";

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
     * @throws TaskForceException
     * @throws Exception
     */
    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $city = ArrayHelper::getValue($attributes, 'city')['title'];
        $first_name = ArrayHelper::getValue($attributes, 'first_name');
        $last_name = ArrayHelper::getValue($attributes, 'last_name');

        /* @var Auth $auth */
        $auth = Auth::find()->where(['source' => $this->client->getId(), 'source_id' => $id])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                $user->date_login = new DateTime('now');
            } else { // signup
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash(
                        'error',
                        Yii::t('app', self::MESSAGE, ['client' => $this->client->getTitle()]),
                    );
                } else {
                    $password = Yii::$app->security->generateRandomString(9);
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $user = new User();
                        $user->city_id = City::findIdByName($city) || 1;
                        $user->email = $email;
                        $user->name = ($first_name . ' ' . $last_name);
                        $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
                        $user->role = UserRole::CUSTOMER;
                        $user->auth_key = Yii::$app->security->generateRandomString();
                        $user->generatePasswordResetToken();
                        $user->save();
                        $profile = new Profile();
                        $profile->user_id = $user->id;
                        $profile->save();
                        $transaction->commit();
                    } catch (Exception) {
                        $transaction->rollBack();
                    }
                    $transaction = User::getDb()->beginTransaction();
                    if (isset($user->id)) {
                        $auth = new Auth(
                            [
                                'user_id' => $user->id,
                                'source' => $this->client->getId(),
                                'source_id' => (string)$id,
                            ]
                        );
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
            }
        }
        if (!Yii::$app->user->isGuest) { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth(
                    [
                        'user_id' => Yii::$app->user->id,
                        'source' => $this->client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]
                );
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
            } else { // there's existing auth
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
}