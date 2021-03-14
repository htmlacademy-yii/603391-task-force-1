<?php

/* @var $this yii\web\View */
/** @var array $cities */
/** @var AccountForm $modelAccountForm */
/** @var NotificationsFilterForm $modelNotificationsForm */

use frontend\models\forms\AccountForm;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\NotificationsFilterForm;
use TaskForce\widgets\DropZoneWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="account__redaction-wrapper">
            <h1>Редактирование настроек профиля</h1>
            <?php
            $form = ActiveForm::begin(
                [
                    'action' => '/account/index',
                    'enableClientValidation' => false,
                    'fieldConfig' => [
                        'template' => "{label}{input}{error}",
                        'inputOptions' => ['class' => 'input textarea'],
                        'errorOptions' => ['tag' => 'span', 'class' => 'input-error', 'id' => 'profileForm'],
                    ],
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => '',
                    ]
                ]
            ); ?>
            <div class="account__redaction-section">
                <h3 class="div-line">Настройки аккаунта</h3>
                <div class="account__redaction-section-wrapper">
                    <div class="account__redaction-avatar">
                        <img src="<?= Url::base() . '/uploads/avatars/' . $modelAccountForm['avatar'] ?>" width="156"
                             height="156" alt="">
                        <?= $form->field(
                            $modelAccountForm,
                            'avatarFile',
                            [
                                'template' => '{input}{label}',
                                'options' => ['tag' => false],
                            ]
                        )->fileInput(['id' => 'upload-avatar'])
                            ->label('Сменить аватар', ['class' => 'link-regular', 'for' => 'upload-avatar'])

                        ?>
                    </div>
                    <div class="account__redaction">
                        <?= $form
                            ->field(
                                $modelAccountForm,
                                'name',
                                ['options' => ['tag' => 'div', 'class' => 'field-container account__input account__input--name']]
                            )
                            ->label('Ваше имя')
                            ->textInput(
                                [
                                    'placeholder' => 'Титов Денис',
                                    'disabled' => '',
                                ]
                            )
                        ?>

                        <?= $form
                            ->field(
                                $modelAccountForm,
                                'email',
                                ['options' => ['tag' => 'div', 'class' => 'field-container account__input account__input--email']]
                            )
                            ->label('Электронная почта')
                            ->textInput(
                                [
                                    'placeholder' => 'username@mail.ru',
                                    'autofocus' => true,
                                ]
                            )
                        ?>

                        <?= $form
                            ->field(
                                $modelAccountForm,
                                'cityId',
                                ['options' => ['tag' => 'div', 'class' => 'field-container account__input account__input--address']]
                            )
                            ->label('Город')
                            ->dropDownList(
                                $cities,
                                [
                                    'class' => 'multiple-select input multiple-select-big',
                                    'size' => 1
                                ]
                            )
                            ->hint('Укажите город, чтобы находить подходящие задачи'); ?>

                        <?= $form
                            ->field(
                                $modelAccountForm,
                                'birthday',
                                ['options' => ['tag' => 'div', 'class' => 'field-container account__input account__input--date']]
                            )
                            ->label('День рождения')
                            ->textInput(
                                [
                                    'type' => 'date',
                                    'autofocus' => true,
                                    'class' => 'input-middle input input-date'
                                ]
                            )
                        ?>

                        <?= $form
                            ->field(
                                $modelAccountForm,
                                'info',
                                ['options' => ['tag' => 'div', 'class' => 'field-container account__input account__input--info']]
                            )
                            ->label('Информация о себе')
                            ->textarea(
                                [
                                    'rows' => 7,
                                    'placeholder' => 'Place your text',
                                ]
                            )
                        ?>
                    </div>
                </div>
                <h3 class="div-line">Выберите свои специализации</h3>
                <div class="account__redaction-section-wrapper">
                    <div class="search-task__categories account_checkbox--bottom">
                        <?php
                        /** @var CategoriesFilterForm $modelCategoriesForm */
                        foreach ($modelCategoriesForm->attributeLabels() as $key => $label): ?>
                            <?= $form->field(
                                $modelCategoriesForm,
                                sprintf('categories[%s]', $key),
                                [
                                    'template' => '<label class="checkbox__legend">{input}{hint}</label>',
                                    'options' => ['tag' => false]
                                ]
                            )
                                ->checkbox(
                                    [
                                        'class' => 'visually-hidden checkbox__input',
                                        'id' => $key,
                                        'checked' => (bool)$modelCategoriesForm[$key]
                                    ],
                                    false
                                )
                                ->hint(
                                    $label,
                                    ['tag'=>'span',

                                        'class' => false,
                                    ]
                                ); ?>

                        <?php
                        endforeach; ?>
                    </div>
                </div>
                <h3 class="div-line">Безопасность</h3>
                <div class="account__redaction-section-wrapper account__redaction">
                    <?= $form
                        ->field(
                            $modelAccountForm,
                            'newPassword',
                            ['options' => ['tag' => 'div', 'class' => 'account__input']]
                        )
                        ->label('Новый пароль')
                        ->input('password', ['class' => 'input textarea', 'type' => 'password'])
                        ->hint('Длина пароля от 8 символов'); ?>

                    <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                    <?= $form
                        ->field(
                            $modelAccountForm,
                            'repeatPassword',
                            ['options' => ['tag' => 'div', 'class' => 'account__input']]
                        )
                        ->label('Повтор пароля')
                        ->input('password', ['class' => 'input textarea', 'type' => 'password'])
                        ->hint('Длина пароля от 8 символов'); ?>
                </div>
                <?= DropZoneWidget::widget() ?>
                <h3 class="div-line">Контакты</h3>
                <div class="account__redaction-section-wrapper account__redaction">
                    <?= $form
                        ->field(
                            $modelAccountForm,
                            'phone',
                            ['options' => ['tag' => 'div', 'class' => 'account__input']]
                        )->widget(MaskedInput::class, [
                            'mask' => '8(999)999-99-99',
                            'options' => [
                                'class' => 'input textarea',
                                'id' => 'phone',
                            ],
                         ])->label('Телефон')
                    ?>

                    <?= $form
                        ->field(
                            $modelAccountForm,
                            'skype',
                            ['options' => ['tag' => 'div', 'class' => 'account__input']]
                        )
                        ->label('Skype')
                        ->textInput(
                            [
                                'placeholder' => 'DenisT',
                                'class' => 'input textarea',
                            ]
                        ) ?>

                    <?= $form
                        ->field(
                            $modelAccountForm,
                            'telegram',
                            ['options' => ['tag' => 'div', 'class' => 'account__input']]
                        )
                        ->label('Telegram')
                        ->textInput(
                            [
                                'placeholder' => '@DenisT',
                                'class' => 'input textarea',
                            ]
                        ) ?>
                </div>
                <h3 class="div-line">Настройки сайта</h3>
                <h4>Уведомления</h4>
                <div class="account__redaction-section-wrapper account_section--bottom">
                    <div class="search-task__categories account_checkbox--bottom">
                        <?php
                        foreach ($modelNotificationsForm->attributeLabels() as $key => $label): ?>
                            <?= $form->field(
                                $modelNotificationsForm,
                                sprintf('notifications[%s]', $key),
                                [
                                    'template' => '<label class="checkbox__legend">{input}{hint}</label>',
                                    'options' => ['tag' => false]
                                ]
                            )
                                ->checkbox(
                                    [
                                        'class' => 'visually-hidden checkbox__input',
                                        'id' => 'notification' . $key,
                                        'checked' => (bool)$modelNotificationsForm[$key]
                                    ],
                                    false
                                )
                                ->hint(
                                    $label,
                                    ['tag'=>'span',

                                        'class' => false,
                                    ]
                                ); ?>
                        <?php
                        endforeach; ?>
                    </div>
                    <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                        <?= $form->field(
                            $modelAccountForm,
                            'showMyContact',
                            [
                                'template' => '<label class="checkbox__legend">{input}{hint}</label>',
                                'options' => ['tag' => false]
                            ]
                        )
                            ->checkbox(
                                [
                                    'class' => 'visually-hidden checkbox__input',
                                    'checked' => (bool)$modelAccountForm['showMyContact'],
                                    'id' => 'showMyContact'
                                ],
                                false
                            )
                            ->hint(
                                $label,
                                ['tag'=>'span',

                                    'class' => false,
                                ]
                            ); ?>

                        <?= $form->field(
                            $modelAccountForm,
                            'dontShowProfile',
                            [
                                'template' => '<label class="checkbox__legend">{input}{hint}</label>',
                                'options' => ['tag' => false]
                            ]
                        )
                            ->checkbox(
                                [
                                    'class' => 'visually-hidden checkbox__input',
                                    'checked' => (bool)$modelAccountForm['dontShowProfile'],
                                    'id' => 'dontShowProfile'
                                ],
                                false
                            )
                            ->hint(
                                'Не показывать мой профиль',
                                ['tag'=>'span',
                                    'class' => false,
                                ]
                            ); ?>
                    </div>
                </div>
            </div>
            <?= Html::submitButton('Сохранить изменения', ['class' => 'button button__registration']); ?>
            <?php
            ActiveForm::end(); ?>
        </section>
    </div>
</main>
