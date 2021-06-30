<?php

/** @var array $model */

use TaskForce\Helpers\Declination;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

?>
<div class="new-task__card">
    <div class="new-task__title">
        <a href="<?= Url::to(['tasks/view', 'id' => $model['id']]) ?>" class="link-regular">
            <h2><?= HtmlPurifier::process($model['name']) ?></h2></a>
        <a class="new-task__type link-regular"
           href="<?= Url::to(['tasks/index/', 'category' => $model['category_id']]) ?>">
            <p><?=
                $model['cat_name'] ?></p></a>
    </div>
    <div class="new-task__icon new-task__icon--<?= $model['icon'] ?>"></div>
    <p class="new-task_description">
        <?= HtmlPurifier::process($model['description']) ?>
    </p>
    <b class="new-task__price new-task__price--translation"><?= $model['budget'] ?><b> ₽</b></b>
    <p class="new-task__place"><?= $model['address'] ?? '' ?></p>
    <span class="new-task__time"><?= Declination::getTimeAfter($model['date_add']) ?? '' ?></span>
</div>

