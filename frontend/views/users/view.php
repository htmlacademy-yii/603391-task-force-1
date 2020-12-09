<?php

/* @var $this yii\web\View */
/** @var array $modelUser */
/** @var array $modelsOpinions */
/** @var array $works */
/** @var array $specializations */

use TaskForce\Helpers\Declination;
use TaskForce\widgets\PhotosWidget;
use TaskForce\widgets\RatingWidget;
use yii\helpers\Url;

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">
                <div class="user__card">
                    <img src="<?=
                    Url::base() . '/uploads/avatars/' . $modelUser['avatar'] ?>" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= $modelUser['name'] ?></h1>
                        <p>Россия, Санкт-Петербург, <?= Declination::getTimeAfter((string)$modelUser['birthday']) ?></p>
                        <div class="profile-mini__name five-stars__rate">
                            <?= RatingWidget::widget(['rate' => $modelUser['rate']]) ?>
                        </div>
                        <b class="done-task">Выполнил <?php
                            echo $modelUser['countTask'];
                            $words = new Declination('заказ', 'заказа', 'заказов');
                            echo $words->getWord($modelUser['countTask']) ?></b>
                        <b class="done-review">Получил <?php
                            echo count($modelsOpinions);
                            $words = new Declination('отзыв', 'отзыва', 'отзывов');
                            echo $words->getWord(count($modelsOpinions));
                                 ?></b>
                    </div>
                    <div class="content-view__headline user__card-bookmark
                    <?=($modelUser['favorite'])?'user__card-bookmark--current':'user__card-bookmark'?>">
                        <span>Был на сайте <?= Declination::getTimeAfter($modelUser['date_login']) ?> назад</span>
                        <a href="<?= Url::to(['users/bookmark','userId'=>$modelUser['user_id']])?>">
                            <b></b>
                        </a>
                    </div>
                </div>
                <div class="content-view__description">
                    <p><?= $modelUser['about'] ?></p>
                </div>
                <div class="user__card-general-information">
                    <div class="user__card-info">
                        <h3 class="content-view__h3">Специализации</h3>
                        <div class="link-specialization">
                            <?= (!$specializations) ? '-' : '' ?>
                            <?php foreach ($specializations as $key => $specialization): ?>
                                <a href="#" class="link-regular"><?= $specialization['name'] ?></a>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="content-view__h3">Контакты</h3>
                        <div class="user__card-link">
                            <a class="user__card-link--tel link-regular" href="#"><?= $modelUser['phone'] ?></a>
                            <a class="user__card-link--email link-regular" href="#"><?= $modelUser['email'] ?></a>
                            <a class="user__card-link--skype link-regular" href="#"><?= $modelUser['skype'] ?></a>
                        </div>
                    </div>
                    <?= PhotosWidget::widget(['userId'=>$modelUser['user_id']])?>
                </div>
            </div>
            <?php if (count($modelsOpinions)):?>
            <div class="content-view__feedback">
                <h2>Отзывы <span>(<?= count($modelsOpinions) ?>)</span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($modelsOpinions as $key => $modelOpinion): ?>
                        <div class="feedback-card__reviews">
                            <p class="link-task link">Задание
                                <a href="<?= Url::to(['tasks/view','id'=>$modelOpinion['task_id']])?>" class="link-regular">«<?= $modelOpinion['taskName'] ?>»</a>
                            </p>
                            <div class="card__review">
                                <a href="#"><img src="<?=
                                    Url::base() . '/uploads/avatars/' . $modelOpinion['avatar'] ?>" width="55" height="54"
                                                 alt=""></a>
                                <div class="feedback-card__reviews-content">
                                    <p class="link-name link">
                                        <a  class="link-regular"><?= $modelOpinion['userName'] ?></a>
                                    </p>
                                    <p class="review-text">
                                        <?= $modelOpinion['description'] ?>
                                    </p>
                                </div>
                                <?= RatingWidget::widget(['rate' => $modelUser['rate'], 'type' => 2]) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif;?>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__chat">

            </div>
        </section>
    </div>
</main>
