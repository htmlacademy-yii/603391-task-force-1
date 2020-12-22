<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php

    use frontend\models\forms\LoginForm;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    $form = ActiveForm::begin(
        [
            'action' => 'landing/login',

            'fieldConfig' => [
                'enableClientValidation' => true,
                'template' => "<p>{label}{error}{input}</p>",
                'labelOptions' => ['class' => 'form-modal-description'],
                'inputOptions' => ['class' => 'enter-form-email input input-middle'],
                'errorOptions' => ['tag' => 'font', 'color' => 'red'],
            ],
        ]
    );

    echo $form
        ->field($this->context->loginForm, 'email')
        ->label('Email')
        ->input(
            'email',
            [
                'placeholder' => 'username@mail.ru',
                'autofocus' => true,
            ]
        );

    /** @var LoginForm $loginForm */
    echo $form
        ->field($loginForm, 'password')
        ->label('Пароль')
        ->input('password');

    echo Html::submitButton('Войти', ['class' => 'button']);
    ActiveForm::end();
    ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>