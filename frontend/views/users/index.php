<?php

use frontend\widgets\Rating;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use frontend\widgets\UsersFilters;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/** @var Pagination $pagination */
/** @var string $sortType */
/** @var array $modelsUsers */
/** @var UsersFilterForm $modelUsersFilter */
/** @var array $models */
/** @var CategoriesFilterForm $modelCategoriesFilter */

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <?=$this->render('_user__serarch-link',compact('sortType'));?>
            <?php foreach ($modelsUsers as $user): ?>
                <div class="content-view__feedback-card user__search-wrapper">
                    <div class="feedback-card__top">
                        <div class="user__search-icon">
                            <a href="<?= Url::to(['users/view', 'id' => $user['profile_id']]) ?>"><img
                                    src="<?= Url::base() . '/uploads/avatars/' . $user['avatar'] ?>" width="65" height="65" alt=""></a>
                            <span><?= $user['countTasks'] ?> заданий</span>
                            <span><?= $user['countReplies'] ?> отзывов</span>
                        </div>

                        <div class="feedback-card__top--name user__search-card">
                            <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $user['id']]) ?>"
                                                    class="link-regular"><?= $user['name'] ?></a></p>
                            <?= Rating::widget(['rate' => $user['rate']]) ?>
                            <p class="user__search-content">
                                <?= $user['about'] ?>
                            </p>
                        </div>
                        <span class="new-task__time">Был на сайте </br> <?= $user['afterTime'] ?> назад</span>
                    </div>
                    <div class="link-specialization user__search-link--bottom">
                        <?php
                        if (isset($user['categories'])) {
                            foreach ($user['categories'] as $key => $item): ?>
                                <a href="<?= Url::to(['users/index/', 'category' => $item['id']]) ?>" class="link-regular"><?= $item['name'] ?></a>
                            <?php endforeach;
                        } ?>
                    </div>
                </div>
            <?php endforeach ?>
            <div class="new-task__pagination">
                <?
                echo LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => ['class' => 'new-task__pagination-list'],
                    'maxButtonCount' => 3,
                    'pageCssClass' => 'pagination__item',
                    'nextPageCssClass' => 'pagination__item',
                    'prevPageCssClass' => 'pagination__item',
                    'activePageCssClass' => 'pagination__item--current',
                    'nextPageLabel' => '',
                    'prevPageLabel' => '',
                    'registerLinkTags' => true
                ]); ?>
            </div>


        </section>
                    <?= UsersFilters::widget(compact('modelCategoriesFilter', 'modelUsersFilter'));?>
    </div>
</main>


