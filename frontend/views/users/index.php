<?php

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use TaskForce\SortingUsers;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/** @var \yii\data\Pagination $pagination */
/** @var string $sortType */
/** @var array $modelsUsers */
/** @var UsersFilterForm $modelUsersFilter */
/** @var array $models */
/** @var CategoriesFilterForm $modelCategoriesFilter */

$this->title = 'TaskForce - Исполнители';
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <div class="user__search-link">
                <p>Сортировать по:</p>
                <ul class="user__search-list">
                    <?php foreach (SortingUsers::SORTS as $sort): ?>
                        <li class="user__search-item <?= ($sortType == $sort) ? ' user__search-item--current' : '' ?>">
                            <a href="<?= URL::to(['users/index', 'sortType' => $sort]) ?>"
                               class="link-regular"><?= $sort ?></a>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>

            <?php foreach ($modelsUsers as $user): ?>
                <div class="content-view__feedback-card user__search-wrapper">
                    <div class="feedback-card__top">
                        <div class="user__search-icon">
                            <a href="<?= Url::to(['users/view', 'id' => $user['id']]) ?>"><img
                                    src="../img/<?= $user['avatar'] ?>" width="65" height="65" alt=""></a>
                            <span><?= $user['countTasks'] ?> заданий</span>
                            <span><?= $user['countReplies'] ?> отзывов</span>
                        </div>

                        <div class="feedback-card__top--name user__search-card">
                            <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $user['id']]) ?>"
                                                    class="link-regular"><?= $user['name'] ?></a></p>

                            <?= str_repeat('<span></span>', $user['rate']); ?>
                            <?= str_repeat('<span class="star-disabled"></span>', 5 - $user['rate']); ?>
                            <b><?= $user['rate'] ?></b>
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
                                <a href="#" class="link-regular"><?= $item['name'] ?></a>
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
        <section class="search-task">
            <div class="search-task__wrapper">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'name' => 'users',
                        'class' => 'search-task__form'],
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                        ],
                    ],
                ]); ?>
                <fieldset class="search-task__categories">
                    <legend>Категории</legend>
                    <?php

                    foreach ($modelCategoriesFilter->attributeLabels() as $key => $label): ?>
                        <?= $form
                            ->field($modelCategoriesFilter, sprintf('categories[%s]', $key), [
                                'template' => '{input}{label}'
                            ])
                            ->checkbox(
                                ['class' => 'visually-hidden checkbox__input',
                                    'id' => $key,
                                    'checked' => (bool)$modelCategoriesFilter[$key]
                                ],
                                false)
                            ->label($label, [
                                'for' => $key,
                                'class' => false,
                                'tag' => false
                            ]); ?>
                    <?php endforeach; ?>
                </fieldset>
                <fieldset class="search-task__categories">
                    <legend>Дополнительно</legend>

                    <?php
                    foreach ($modelUsersFilter->checkboxesLabels() as $key => $value) {
                        echo $form->field($modelUsersFilter, $key, ['template' => '{input}{label}']
                        )->checkbox(
                            ['class' => 'visually-hidden checkbox__input', 'id' => $key],
                            false)->label($value, ['for' => $key, 'class' => false]);
                    } ?>
                </fieldset>

                <?= $form->field($modelUsersFilter, 'searchName', ['template' => '{label}{input}'])
                    ->input('search',
                        ['class' => "input-middle input"])
                    ->label($modelUsersFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]); ?>

                <?= Html::submitButton('Искать', ['class' => 'button']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</main>


