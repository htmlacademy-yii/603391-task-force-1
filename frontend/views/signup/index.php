<?php

/* @var $this yii\web\View */
/** @var SignupForm $model */
/** @var array $cities */
/** @var string $userCityKey */

use frontend\models\forms\SignupForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="registration__user">
            <h1>Регистрация аккаунта</h1>
            <div class="registration-wrapper">
                <?php
                $form = ActiveForm::begin(
                    [
                        'action' => '/signup/index',
                        'enableClientValidation' => true,
                        'fieldConfig' => [
                            'template' => "</br>{label}</br>{input}</br>{hint}</br>{error}",
                            'inputOptions' => ['class' => 'input textarea input-wide'],
                            'errorOptions' => ['tag' => 'span', 'class' => 'input-error'],
                            'hintOptions' => ['tag' => 'span'],
                        ],
                        'options' => [
                            'class' => 'registration__user-form form-create',
                        ]
                    ]
                );

                echo $form
                    ->field($model, 'email')
                    ->label('Электронная почта')
                    ->input('email',
                            [
                            'placeholder' => 'username@mail.ru',
                            'autofocus' => true,
                        ]
                    )
                    ->hint('Введите валидный адрес электронной почты');

                echo $form
                    ->field($model, 'username')
                    ->label('Ваше имя')
                    ->input( 'text',                      [
                            'placeholder' => 'Фамилия Имя',
                        ]
                    )
                    ->hint('Введите ваше имя и фамилию');

                echo $form
                    ->field($model, 'cityId')
                    ->label('Город проживания')
                    ->dropDownList(
                        $cities,
                        ['options' =>
                            [
                               $userCityKey => ['selected' => true]
                            ],
                            'class' => 'multiple-select input town-select registration-town input-wide',
                            'size' => 1,
                        ]
                    )
                    ->hint('Укажите город, чтобы находить подходящие задачи');

                echo $form
                    ->field($model, 'password')
                    ->label('Пароль')
                    ->input('password')
                    ->hint('Длина пароля от 8 символов');

                echo Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']);
                ActiveForm::end();
                ?>
            </div>
        </section>
    </div>
</main>
