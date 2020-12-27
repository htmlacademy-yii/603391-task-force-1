<?php

/* @var $content string */
/* @var $selectedCity string */

use TaskForce\Constant\UserRole;
use TaskForce\widgets\AccountWidget;
use TaskForce\widgets\BulbWidget;
use TaskForce\widgets\CitySelectorWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\Menu;

$loggedUser = Yii::$app->user->identity;
AppAsset::register($this);
?>
    <?php
    $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->context->title ?? Yii::$app->params['AppName']) ?></title>
        <?php
        $this->head() ?>
    </head>
    <body>
    <?php
    $this->beginBody() ?>

    <div class="table-layout">
        <header class="page-header">
            <div class="main-container page-header__container">
                <div class="page-header__logo">
                    <a href="<?= Url::to(['landing/index']) ?>">
                        <?= $this->render('_logo', ['class' => 'page-header__logo-image']) ?>
                    </a>
                </div>
                <div class="header__nav">
                    <?php
                    if ($loggedUser->id) {
                        echo Menu::widget(
                            [
                                'items' => [
                                    ['label' => 'Задания', 'url' => ['tasks/index']],
                                    ['label' => 'Исполнители', 'url' => ['users/index']],
                                    ['label' => 'Создать задание', 'url' => ['task/create'],
                                        'visible'=> ($loggedUser->role === UserRole::CUSTOMER)],
                                    ['label' => 'Мой профиль', 'url' => ['account/index']],
                                ],
                                'options' => [
                                    'class' => 'header-nav__list site-list',
                                ],
                                'encodeLabels' => 'false',
                                'activeCssClass' => 'site-list__item site-list__item--active',
                                'itemOptions' => ['class' => 'site-list__item'],
                            ]
                        );
                    } ?>
                </div>
                <?php
                if (Yii::$app->request->pathInfo !== 'signup/index' && $loggedUser): ?>
                    <?= CitySelectorWidget::widget(); ?>
                    <?= BulbWidget::widget(); ?>
                    <?= AccountWidget::widget(); ?>
                <?php
                endif; ?>
            </div>
        </header>

        <?= Alert::widget() ?>
        <?= $content ?>

        <footer class="page-footer">
            <div class="main-container page-footer__container">
                <?= $this->render('_footerInfo'); ?>
                <?php
                if ($loggedUser->id): ?>
                    <div class="page-footer__links">
                        <?php
                        echo Menu::widget(
                            [
                                'items' => [
                                    ['label' => 'Задания', 'url' => ['tasks/index']],
                                    ['label' => 'Мой профиль', 'url' => ['/account/index']],
                                    ['label' => 'Исполнители', 'url' => ['/users/index']],
                                    ['label' => 'Регистрация', 'url' => ['/signup/index']],
                                    ['label' => 'Создать задание', 'url' => ['/task/create']],
                                    ['label' => 'Справка', 'url' => ['#']],
                                ],
                                'options' => [
                                    'class' => 'links__list',
                                ],
                                'encodeLabels' => 'false',
                                'itemOptions' => ['class' => 'links__item'],
                            ]
                        ); ?>
                    </div>
                <?php
                endif; ?>
                <?= $this->render('_footerCopyright'); ?>
                <?php
                if (
                    Yii::$app->controller->id === 'signup'): ?>
                    <div class="clipart-woman">
                        <img src="<?= Url::to('/img/clipart-woman.png') ?>" width="238" height="450" alt="">
                    </div>
                    <div class="clipart-message">
                        <div class="clipart-message-text">
                            <h2>Знаете ли вы, что?</h2>
                            <p>После регистрации вам будет доступно более
                                двух тысяч заданий из двадцати разных категорий.</p>
                            <p>В среднем, наши исполнители зарабатывают
                                от 500 рублей в час.</p>
                        </div>
                    </div>
                <?php
                endif; ?>
            </div>
        </footer>
    </div>
    <div class="overlay"></div>

    <?php
    $this->endBody() ?>
    </body>
    </html>
    <?php
    $this->endPage() ?>
