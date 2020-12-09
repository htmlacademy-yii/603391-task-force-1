<?php

/* @var $this View */
/** @var string $content */

use frontend\assets\LandingAsset;
use TaskForce\widgets\LoginFormWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\widgets\Alert;

LandingAsset::register($this);
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?php
    $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->head() ?>
</head>
<body class="landing">
<?php
$this->beginBody() ?>
<div class="table-layout">
    <header class=" page-header--index">
        <div class="main-container page-header__container page-header__container--index">
            <div class="page-header__logo--index">
                <a>
                    <?= $this->render('_logo',['class' => 'logo-image--index'])?>
                </a>
                <p>Работа там, где ты!</p>
            </div>
            <div class="header__account--index">
                <a href="#" class="header__account-enter open-modal" data-for="enter-form">
                    <span>Вход</span></a>
                или
                <a href="<?= Url::to(['signup/index']) ?>" class="header__account-registration">
                    Регистрация
                </a>
            </div>
        </div>
    </header>

    <?= $content ?>
    <footer class="page-footer">
        <div class="main-container page-footer__container">
            <?=$this->render('_footerInfo')?>
            <?=$this->render('_footerCopyRight')?>
        </div>
    </footer>
    <?= LoginFormWidget::widget() ?>
</div>
<?php
if (Yii::$app->session->hasFlash('login-error')): ?>
    <section class="modal enter-form form-modal landing-task" style="display: block">
        <p>
            <h3>
                <?php
                echo Yii::$app->session->getFlash('login-error'); ?>
            </h3>
        </p>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
<?
endif; ?>

<div class="overlay">
    <?= Alert::widget() ?>
</div>
<?php
$this->endBody() ?>
</body>
</html>
<?php
$this->endPage() ?>