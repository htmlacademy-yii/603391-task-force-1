<?php

/* @var $this yii\web\View */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'TaskForce - Регистрация аккаунта';

?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="registration__user">
            <h1>Регистрация аккаунта</h1>
            <div class="registration-wrapper">
                <?php  $form = ActiveForm::begin([
                    'action' => '/signup/index',
                    'enableClientValidation' => true,
                    'fieldConfig' => [
                        'template' => "{label}</br>{input}</br>{hint}{error}",
                        'inputOptions' => ['class' => 'input textarea col-xs-12'],
                        'labelOptions'=> [ 'errorCssClass' => 'input-danger'],
                        'errorOptions'=> ['tag' => 'span'],
                        'hintOptions' => ['tag' => 'span'],
                    ],
                    'options' => [
                        'class' => 'registration__user-form form-create',
                    ]
                ]);

                echo $form
                    ->field($model, 'email')
                    ->label('Электронная почта')
                    ->textarea([
                        'rows' => 1,
                        'placeholder' => 'username@mail.ru',
                        'autofocus' => true,
                    ])
                    ->hint('Введите валидный адрес электронной почты');

                echo $form
                    ->field($model, 'username')
                    ->label('Ваше имя')
                    ->textarea([
                        'rows' => 1,
                        'placeholder' => 'Фамилия Имя',
                    ])
                    ->hint('Введите ваше имя и фамилию');


                /** @var array $cities */
                echo $form
                    ->field($model, 'cityId')
                    ->label('Город проживания')
                    ->dropDownList($cities, [
                        'class' => 'multiple-select input town-select registration-town col-xs-12',
                        'size' => 1
                    ])
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
