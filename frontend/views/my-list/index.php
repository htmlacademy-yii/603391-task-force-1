<?php

/* @var $this yii\web\View */
/** @var TasksFilterForm $modelTasksFilter */
/** @var Task $modelsTasks */
/** @var CategoriesFilterForm $modelCategoriesFilter */
/** @var Pagination $pagination */
/** @var array $modelTasks */
/** @var string $currentFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\Task;
use frontend\models\forms\TasksFilterForm;
use frontend\widgets\StarRating;
use TaskForce\TaskEntity;
use yii\data\Pagination;
use yii\helpers\Url;

?>
<main class="page-main">
    <div class="main-container page-container">

        <?= $this->render('_filters_menu-toggle',['currentFilter'=> $currentFilter]) ?>


        <section class="my-list">
            <div class="my-list__wrapper">
                <h1>Мои задания</h1>
                <?php

                foreach ($modelTasks as $modelTask):?>
                    <div class="new-task__card">
                        <div class="new-task__title">
                            <a href="<?= URL::to(['tasks/view', 'id' => $modelTask['id']]) ?>" class="link-regular">
                                <h2><?= $modelTask['name'] ?></h2>
                            </a>
                            <a class="new-task__type link-regular"
                               href="<?= Url::to(['tasks/index/', 'category' => $modelTask['category_id']]) ?>">
                                <p><?= $modelTask['cat_name'] ?></p></a>
                        </div>
                        <div class="task-status
                        <?php
                        switch ($modelTask['status']) {
                            case TaskEntity::STATUS_NEW:
                                echo 'new-status';
                                break;
                            case TaskEntity::STATUS_COMPLETE:
                                echo 'done-status';
                                break;
                            default:
                                break;
                        } ?>
                        "><?= TaskEntity::STATUS_TO_NAME[$modelTask['status']] ?></div>
                        <p class="new-task_description">
                            <?= $modelTask['description'] ?>
                        </p>
                        <div class="feedback-card__top ">
                            <a href="<?= Url::to(['users/view', 'id' => $modelTask['customer_id']]) ?>">
                                <img src="<?= Url::base() . '/uploads/avatars/' . ($response['avatar'] ?? 'no-avatar.jpg') ?>" width="36" height="36" alt="avatar">
                            </a>
                            <div class="feedback-card__top--name my-list__bottom">
                                <p class="link-name"><a href="#" class="link-regular"><?= $modelTask['user_name'] ?></a>
                                </p>
                                <a href="<?= Url::to(['tasks/view', 'id' => $modelTask['id']]) ?>"
                                   class="my-list__bottom-chat  my-list__bottom-chat--new"><b><?= $modelTask['messages']??0 ?></b></a>
                                <?= StarRating::widget(['rate' =>  $modelTask['rate']]) ?>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach; ?>
            </div>
        </section>
    </div>
</main>
