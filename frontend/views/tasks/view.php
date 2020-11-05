<?php

/* @var $this yii\web\View */

/** @var TasksFilterForm $modelTasksFilter */
/** @var CategoriesFilterForm $modelCategoriesFilter */
/** @var array $modelTask */
/** @var array $modelsResponse */
/** @var array $modelTaskUser */
/** @var ResponseTaskForm $responseTaskForm */
/** @var CompleteTaskForm $completeTaskForm */
/** @var bool $existsUserResponse */
/** @var array $availableActions */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\forms\TasksFilterForm;
use TaskForce\Actions\CancelAction;
use TaskForce\Actions\CompleteAction;
use TaskForce\Actions\RefuseAction;
use TaskForce\Actions\ResponseAction;
use TaskForce\Constant\UserRole;
use TaskForce\Helpers\Declination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'TaskForce - Задачи';
$currentUserId = Yii::$app->user->getId();

$this->registerJSFile('/js/main.js');

?>
<main class="page-main">
    <div class="main-container page-container">

        <section class="content-view">
            <div class="content-view__card">
                <div class="content-view__card-wrapper">
                    <div class="content-view__header">
                        <div class="content-view__headline">
                            <h1><?=
                                $modelTask['name'] ?></h1>
                            <span>Размещено в категории
                                    <a href="<?= Url::to(['tasks/index/', 'category' => $modelTask['category_id']]) ?>"
                                       class="link-regular"><?= $modelTask['cat_name'] ?></a>
                                    <?= $modelTask['afterTime'] ?> назад</span>
                        </div>
                        <b class="new-task__price new-task__price--<?= $modelTask['icon'] ?> content-view-price"><?= $modelTask['budget'] ?>
                            <b> ₽</b></b>
                        <div class="new-task__icon new-task__icon--<?= $modelTask['icon'] ?> content-view-icon"></div>
                    </div>
                    <div class="content-view__description">
                        <h3 class="content-view__h3">Общее описание</h3>
                        <p><?= $modelTask['description'] ?></p>
                    </div>
                    <div class="content-view__attach">
                        <h3 class="content-view__h3">Вложения</h3>
                        <?php
                        /** @var array $modelsFiles */
                        if (count($modelsFiles) == 0) {
                            echo 'отсутствуют';
                        }
                        foreach ($modelsFiles as $key => $file):?>
                            <a href="<?= Url::to(['site/file', 'id' => $file['id']]) ?>"
                               title="<?= $file['filename'] ?>">
                                <?= (strlen($file['filename']) > 30)
                                    ? (substr($file['filename'], 0, 30) . '...')
                                    : $file['filename'] ?></a>
                        <?php
                        endforeach; ?>
                    </div>
                    <div class="content-view__location">
                        <h3 class="content-view__h3">Расположение</h3>
                        <div class="content-view__location-wrapper">
                            <div class="content-view__map">
                                <a href="#"><img src="../../img/map.jpg" width="361" height="292"
                                                 alt="Москва, Новый арбат, 23 к. 1"></a>
                            </div>
                            <div class="content-view__address">
                                <span class="address__town">Москва</span><br>
                                <span><?= $modelTask['address'] ?></span>
                                <p>Вход под арку, код домофона 1122</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-view__action-buttons">

                    <?php
                    foreach ($availableActions as $key => $action) {
                        switch ($action) {
                            case ResponseAction::getTitle():
                                if ($existsUserResponse) {
                                    break;
                                };
                                echo '<button class="button button__big-color response-button open-modal"
                                type="button" data-for="response-form">Откликнуться</button>';
                                break;
                            case RefuseAction::getTitle():
                                echo '<button class="button button__big-color refusal-button open-modal"
                            type="button" data-for="refuse-form">Отказаться</button>';
                                break;
                            case CompleteAction::getTitle():
                                echo '<button class="button button__big-color request-button open-modal"
                            type="button" data-for="complete-form">Завершить</button>';
                                break;
                            case CancelAction::getTitle():
                                echo '<button class="button button__big-color refusal-button open-modal"
                            type="button" data-for="cancel-form">Отменить</button>';
                                break;
                            default:
                                break;
                        }
                    } ?>

                </div>
            </div>
            <div class="content-view__feedback">
                <?php
                if (!empty($modelsResponse)): ?>
                    <h2>Отклики <span>(<?= count($modelsResponse) ?>)</span></h2>
                <?php
                endif; ?>

                <div class="content-view__feedback-wrapper">

                    <?php
                    foreach ($modelsResponse as $key => $response): ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="<?= Url::to(['users/view', 'id' => $response['user_id']]) ?>">
                                    <img src="../../img/<?= $response['avatar'] ?>" width="55" height="55" alt="avatar"></a>
                                <div class="feedback-card__top--name">
                                    <p><a href="<?= Url::to(['users/view', 'id' => $response['user_id']]) ?>"
                                          class="link-regular"><?= $response['name'] ?></a></p>
                                    <?= str_repeat('<span></span>', $response['rate']); ?>
                                    <?= str_repeat('<span class="star-disabled"></span>', 5 - $response['rate']); ?>
                                    <b><?= $response['rate'] ?></b>
                                </div>
                                <span class="new-task__time"><?= Declination::getTimeAfter(
                                        (string)$response['created_at']
                                    ) ?> назад</span>
                            </div>
                            <div class="feedback-card__content">
                                <p>
                                    <?= $response['description'] ?>
                                </p>
                                <span><?= $response['price'] ?> ₽</span>
                            </div>
                            <?php


                            if (Yii::$app->user->identity->role === UserRole::CUSTOMER
                                && ((int)$modelTask['customer_id']) === $currentUserId
                                && ($response['status'] === TaskForce\ResponseEntity::STATUS_NEW)
                                && ($modelTask['status'] === TaskForce\TaskEntity::STATUS_NEW)
                            ):?>
                                <div class="feedback-card__actions">

                                    <?= Html::a(
                                        'Подтвердить',
                                        [
                                            'response/confirm',
                                            'id' => $response['id']
                                        ],
                                        ['class' => 'button__small-color request-button button']
                                    ) ?>

                                    <?= Html::a(
                                        'Отказать',
                                        [
                                            'response/cancel',
                                            'id' => $response['id']
                                        ],
                                        ['class' => 'button__small-color refusal-button button']
                                    ) ?>

                                </div>
                            <?
                            endif; ?>
                        </div>
                    <?php
                    endforeach; ?>
                </div>
            </div>
        </section>

        <?php
        if (!empty($modelTaskUser)): ?>

            <section class="connect-desk">
                <div class="connect-desk__profile-mini">
                    <div class="profile-mini__wrapper">

                        <h3><?php
                            $showExecutor = ((int)$modelTask['customer_id'] === $currentUserId
                                && $modelTask['executor_id'] !== null);

                            echo ($showExecutor) ? 'Исполнтель' : 'Заказчик' ?></h3>
                        <div class="profile-mini__top">
                            <img src="../../img/<?= $modelTaskUser['avatar'] ?>" width="62" height="62"
                                 alt="Аватар <?= ($showExecutor) ? 'исполнтеля' : 'заказчика' ?>">
                            <div class="profile-mini__name five-stars__rate">
                                <p><?= $modelTaskUser['name'] ?></p>
                                <?= str_repeat('<span></span>', $modelTaskUser['rate']); ?>
                                <?= str_repeat('<span class="star-disabled"></span>', 5 - $modelTaskUser['rate']); ?>
                                <b><?= $modelTaskUser['rate'] ?></b>
                            </div>
                        </div>
                        <p class="info-customer"><span><?= $modelTaskUser['countTask'] ?> заданий</span>
                            <span class="last-"><?= Declination::getTimeAfter(
                                    $modelTaskUser['date_add']
                                ) ?> на сайте</span>
                        </p>
                        <?php
                        if ($showExecutor): ?>
                            <a href="<?= Url::to(['users/view', 'id' => $modelTaskUser['user_id']]) ?>"
                               class="link-regular">Смотреть профиль</a>
                        <?php
                        endif; ?>
                    </div>
                </div>
                <div class="connect-desk__chat">
                    <h3>Переписка</h3>
                    <div class="chat__overflow">
                        <div class="chat__message chat__message--out">
                            <p class="chat__message-time">10.05.2019, 14:56</p>
                            <p class="chat__message-text">Привет. Во сколько сможешь
                                приступить к работе?</p>
                        </div>
                        <div class="chat__message chat__message--in">
                            <p class="chat__message-time">10.05.2019, 14:57</p>
                            <p class="chat__message-text">На задание
                                выделены всего сутки, так что через час</p>
                        </div>
                        <div class="chat__message chat__message--out">
                            <p class="chat__message-time">10.05.2019, 14:57</p>
                            <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
                        </div>
                    </div>
                    <p class="chat__your-message">Ваше сообщение</p>
                    <form class="chat__form">
                        <textarea class="input textarea textarea-chat" rows="2" name="message-text"
                                  placeholder="Текст сообщения"></textarea>
                        <button class="button chat__button" type="submit">Отправить</button>
                    </form>
                </div>
            </section>
        <?php
        endif; ?>
    </div>
</main>
<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php

    $form = ActiveForm::begin(
        [
            'action' => ['task/response', 'id' => $modelTask['id']],
            'enableClientValidation' => true,
            'fieldConfig' => [
                'template' => "<p>{label}{input}{error}</p>",
                'labelOptions' => ['class' => 'form-modal-description'],
                'errorOptions' => ['tag' => 'span', 'style' => 'color:red'],
            ],
        ]
    );

    echo $form
        ->field($responseTaskForm, 'payment')
        ->label('Ваша цена')
        ->input(
            'text',
            [
                'class' => 'response-form-payment input input-middle input-money',

            ]
        );

    echo $form
        ->field($responseTaskForm, 'comment')
        ->label('Комментарий')
        ->textarea(
            [
                'rows' => 4,
                'placeholder' => 'Place your text',
                'class' => 'input textarea'
            ]
        );


    echo Html::submitButton(
        'Отправить',
        [
            'class' => 'button modal-button'
        ]
    );

    ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>

</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php
    $form = ActiveForm::begin(
        [
            'action' => ['task/complete', 'id' => $modelTask['id']],
            'fieldConfig' => [
                'template' => "<p>{label}</br>{input}</p>{error}"
            ]
        ]
    ); ?>

    <?=
    Html::activeRadioList(
        $completeTaskForm,
        'completion',
        [
            'yes' => 'Да',
            'difficult' => 'Возникли проблемы'
        ],
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $radio = Html::radio(
                    $name,
                    $checked,
                    [
                        'id' => $value,
                        'value' => $value,
                        'class' => 'visually-hidden completion-input completion-input--' . $value
                    ]
                );
                $label = Html::label(
                    $label,
                    $value,
                    [
                        'class' => 'completion-label completion-label--' . $value
                    ]
                );
                return $radio . $label;
            }
        ]
    ); ?>

    <?= $form->field($completeTaskForm, 'comment', ['template' => '<p>{label}{input}{error}</p>'])
        ->label('Комментарий', ['class' => 'form-modal-description'])
        ->textarea(
            [
                'class' => 'input textarea',
                'rows' => 4,
                'placeholder' => 'Place your text',
                'id' => "completion-comment",
            ]
        ); ?>


    <?= $form->field(
        $completeTaskForm,
        'rating',
        [
            'template' =>
                "<p class=\"form-modal-description\">Оценка
              <div class='feedback-card__top--name completion-form-star'>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
                <span class='star-disabled'></span>
              </div>
          </p>{input}{error}"
        ]
    )->hiddenInput(['id' => 'rating']); ?>
    <?= Html::submitButton(
        'Отправить',
        [
            'class' => 'button modal-button'
        ]
    ) ?>
    <?php
    ActiveForm::end(); ?>


    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отменить задание</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>

    <?php
    $form = ActiveForm::begin(
        [
            'action' => ['task/refuse', 'id' => $modelTask['id']],
        ]
    );

    echo Html::submitButton(
        'Отменить задание',
        [
            'class' => 'button__form-modal refusal-button button'
        ]
    );
    ActiveForm::end(); ?>


    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отменить задание</h2>
    <p>
        Вы собираетесь отменить задание.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>

    <?php
    $form = ActiveForm::begin(
        [
            'action' => ['task/cancel', 'id' => $modelTask['id']],
        ]
    );

    echo Html::submitButton(
        'Отменить задание',
        [
            'class' => 'button__form-modal refusal-button button'
        ]
    );
    ActiveForm::end(); ?>


    <button class="form-modal-close" type="button">Закрыть</button>
</section>


