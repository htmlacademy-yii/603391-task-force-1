<?php

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use TaskForce\widgets\UsersFiltersWidget;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/** @var object $dataProvider */
/** @var string $sortType */
/** @var array $modelsUsers */
/** @var UsersFilterForm $modelUsersFilter */
/** @var array $models */
/** @var CategoriesFilterForm $modelCategoriesFilter */

?>
<main class="page-main">
    <div class="main-container page-container">
        <div style="margin-right: 20px; ">
            <section class="user__search">
        <?= $this->render('_user__serarch-link', compact('sortType')); ?>
            <?php
                echo ListView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'itemView' => '_oneListElement',
                        'emptyText' => '<p>Ничего не найдено</p>',
                        'emptyTextOptions' => [
                            'tag' => 'span'
                        ],
                        'options' => [
                            'tag' => false,
                        ],
                        'itemOptions' => [
                            'tag' => 'div',
                            'class' => 'content-view__feedback-card user__search-wrapper',
                        ],
                        'layout' => '{items}</section>
                                        <div class="new-task__pagination">{pager}</div>
                                        ',
                        'pager' => [
                            'options' => ['class' => 'new-task__pagination-list'],
                            'maxButtonCount' => 3,
                            'pageCssClass' => 'pagination__item',
                            'nextPageCssClass' => 'pagination__item',
                            'prevPageCssClass' => 'pagination__item',
                            'activePageCssClass' => 'pagination__item--current',
                            'nextPageLabel' => '',
                            'prevPageLabel' => '',
                            'registerLinkTags' => true,
                        ],
                    ],
                );

            ?>
    </section>
        </div>
        <?= UsersFiltersWidget::widget(compact('modelCategoriesFilter', 'modelUsersFilter')); ?>
    </div>
</main>


