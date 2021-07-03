<?php

/* @var $this yii\web\View */

/** @var Pagination $pagination */
/** @var array $modelsTasks */
/** @var CategoriesFilterForm $modelCategoriesFilter */
/** @var object $dataProvider */
/** @var object $modelTasksFilter */

use frontend\models\forms\CategoriesFilterForm;
use TaskForce\widgets\TasksFiltersWidget;
use yii\data\Pagination;
use yii\widgets\ListView;
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="new-task">
            <?php
                echo ListView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'itemView' => '_oneListElement',
                        'emptyText' => 'Ничего не найдено',
                        'emptyTextOptions' => [
                            'tag' => 'span'
                        ],
                        'layout' => '<div class="new-task__wrapper">
                          <h1>Новые задания</h1>{items}</div><div class="new-task__pagination">{pager}</div>',
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
        <?php
        echo TasksFiltersWidget::widget(compact('modelCategoriesFilter', 'modelTasksFilter')) ?>
    </div>
</main>
