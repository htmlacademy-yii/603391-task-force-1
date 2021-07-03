<?php
/** @var array $model */

use TaskForce\Helpers\Declination;
use TaskForce\widgets\RatingWidget;
use yii\helpers\Url;
?>
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <a href="<?= Url::to(['users/view', 'id' => $model['profile_id']]) ?>"><img
                        src="<?= Url::base() . '/uploads/avatars/' . $model['avatar'] ?>" width="65" height="65" alt=""></a>
            <span><?= $model['countTasks'] ?? 0 ?> заданий</span>
            <span><?= $model['countReplies'] ?? 0?> отзывов</span>
        </div>

        <div class="feedback-card__top--name user__search-card">
            <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $model['id']]) ?>"
                                    class="link-regular"><?=  strip_tags($model['name'])  ?></a></p>
            <?= RatingWidget::widget(['rate' => $model['rate']]) ?>
            <p class="user__search-content">
                <?= strip_tags($model['about']); ?>
            </p>
        </div>
        <span class="new-task__time">Был на сайте  <?= Declination::getTimeAfter(strip_tags($model['date_login'])) ?? ''; ?></span>
    </div>
    <div class="link-specialization user__search-link--bottom">
        <?php
        if (isset($model['categories'])) {
            foreach ($model['categories'] as $key => $item): ?>
                <a href="<?= Url::to(['users/index/', 'category' => $item['id']]) ?>" class="link-regular"><?= $item['name'] ?></a>
            <?php endforeach;
        } ?>
    </div>


