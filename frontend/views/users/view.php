<?php
/* @var $this yii\web\View */
/** @var array $countOpinions */
/** @var array $modelUser */
/** @var array $modelsOpinions */
/** @var array $works */
/** @var array $specializations */
/** @var TasksFilterForm $modelTasksFilter */

/** @var CategoriesFilterForm $modelCategoriesFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use TaskForce\Helpers\Declination;

$this->title = 'TaskForce - Исполнитель';

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">
                <div class="user__card">
                    <img src="../../img/<?= $modelUser['avatar'] ?>" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= $modelUser['name'] ?></h1>
                        <p>Россия, Санкт-Петербург, <?= Declination::getTimeAfter((string)$modelUser['birthday']) ?></p>
                        <div class="profile-mini__name five-stars__rate">
                            <?= str_repeat('<span></span>', $modelUser['rate']); ?>
                            <?= str_repeat('<span class="star-disabled"></span>', 5 - $modelUser['rate']); ?>
                            <b><?= $modelUser['rate'] ?></b>
                        </div>
                        <b class="done-task">Выполнил <?php
                            echo $modelUser['countTask'];
                            $words = new Declination('заказ', 'заказа', 'заказов');
                            echo $words->getWord($modelUser['countTask']) ?></b>
                        <b class="done-review">Получил <?php
                            echo $countOpinions;
                            $words = new Declination('отзыв', 'отзыва', 'отзывов');
                            echo $words->getWord($modelUser['countTask']);
                                 ?></b>
                    </div>
                    <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                        <span>Был на сайте <?= Declination::getTimeAfter($modelUser['date_login']) ?> назад</span>
                        <a href="#"><?= $modelUser['favorite'] ?><b></b></a>
                    </div>
                </div>
                <div class="content-view__description">
                    <p><?= $modelUser['description'] ?></p>
                </div>
                <div class="user__card-general-information">
                    <div class="user__card-info">
                        <h3 class="content-view__h3">Специализации</h3>
                        <div class="link-specialization">
                            <?= (!$specializations) ? '-' : '' ?>
                            <?php

                            foreach ($specializations as $key => $specialization): ?>
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
                    <div class="user__card-photo">
                        <h3 class="content-view__h3">Фото работ</h3>
                        <?= (!$works) ? '-' : '' ?>
                        <?php
                        foreach ($works as $key => $work): ?>
                            <a href="#"><img src="../../img/<?= $work['filename'] ?>" width="85" height="86"
                                             alt="Фото работы"></a>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            <div class="content-view__feedback">
                <h2>Отзывы <span>(<?= $countOpinions ?>)</span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($modelsOpinions as $key => $modelOpinion): ?>
                        <div class="feedback-card__reviews">
                            <p class="link-task link">Задание
                                <a href="#" class="link-regular">«<?= $modelOpinion['taskName'] ?>»</a>
                            </p>
                            <div class="card__review">
                                <a href="#"><img src="../../img/<?= $modelOpinion['avatar'] ?>" width="55" height="54"
                                                 alt=""></a>
                                <div class="feedback-card__reviews-content">
                                    <p class="link-name link">
                                        <a href="#" class="link-regular"><?= $modelOpinion['userName'] ?></a>
                                    </p>
                                    <p class="review-text">
                                        <?= $modelOpinion['description'] ?>
                                    </p>
                                </div>
                                <div class="card__review-rate">
                                    <p class="five-rate big-rate"><?= $modelOpinion['rate'] ?><span></span></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__chat">

            </div>
        </section>
    </div>
</main>
