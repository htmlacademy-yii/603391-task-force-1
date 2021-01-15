<?php

/* @var $this yii\web\View */
/** @var TasksFilterForm $modelTasksFilter */
/** @var Task $modelsTasks */
/** @var CategoriesFilterForm $modelCategoriesFilter */
/** @var Pagination $pagination */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\Task;
use frontend\models\forms\TasksFilterForm;
use TaskForce\widgets\TasksFiltersWidget;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="new-task">
            <div class="new-task__wrapper">
                <h1>Новые задания</h1>
                <?php foreach ($modelsTasks as $task): ?>
                    <div class="new-task__card">
                        <div class="new-task__title">
                            <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                                <h2><?= $task['name'] ?></h2></a>
                            <a class="new-task__type link-regular"
                               href="<?= Url::to(['tasks/index/', 'category' => $task['category_id']]) ?>">
                                <p><?= $task['cat_name'] ?></p></a>
                        </div>
                        <div class="new-task__icon new-task__icon--<?= $task['icon'] ?>"></div>
                        <p class="new-task_description">
                            <?= $task['description'] ?>
                        </p>
                        <b class="new-task__price new-task__price--translation"><?= $task['budget'] ?><b> ₽</b></b>
                        <p class="new-task__place"><?= $task['address'] ?></p>
                        <span class="new-task__time"><?= $task['afterTime'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
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

        <?= TasksFiltersWidget::widget(compact('modelCategoriesFilter', 'modelTasksFilter'))?>

    </div>
</main>
