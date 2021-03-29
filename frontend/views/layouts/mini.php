<?php

/* @var $this View */
/* @var $content string */
/* @var $selectedCity string */

use frontend\assets\LandingAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

LandingAsset::register($this);
?>
<div class="table-layout">
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
                        <?= $this->render('_logo', ['class' => 'page-header__logo-image']); ?>
                    </a>
                </div>
            </div>
        </header>
        <main class="page-main">
            <div class="main-container page-container">
                <?= $content ?>
            </div>
        </main>
        <footer class="page-footer">
            <div class="main-container page-footer__container">
                <?= $this->render('_footerCopyright'); ?>
                <?= $this->render('_footerInfo'); ?>
                <?php
                if (Yii::$app->controller->id === 'signup'): ?>
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
    <?php
    $this->endBody() ?>
    </body>
    </html>
    <?php
    $this->endPage() ?>
