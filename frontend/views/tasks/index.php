<?php
/* @var $this yii\web\View */

/** @var TasksFilterForm $modelTasksFilter */

/** @var CategoriesFilterForm $modelCategoriesFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\Task;
use frontend\models\forms\TasksFilterForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'TaskForce - Задания';

?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="new-task">
            <div class="new-task__wrapper">


                <h1>Новые задания</h1>
                <?php /** @var Task $modelsTasks */
                foreach ($modelsTasks as $task): ?>
                    <div class="new-task__card">
                        <div class="new-task__title">
                            <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                                <h2><?= $task['name'] ?></h2></a>
                            <a class="new-task__type link-regular"
                               href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>">
                                <p><?= $task['cat_name'] ?></p></a>
                        </div>
                        <div class="new-task__icon new-task__icon--<?= $task['icon'] ?>"></div>
                        <p class="new-task_description">
                            <?= $task['description'] ?>
                        </p>
                        <b class="new-task__price new-task__price--translation"><?= $task['budget'] ?><b> ₽</b></b>
                        <p class="new-task__place">Санкт-Петербург, Центральный район</p>
                        <span class="new-task__time"><?= $task['afterTime'] ?>  назад</span>
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

        <section class="search-task">
            <div class="search-task__wrapper">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'search-task__form'],
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                        ],
                    ],
                ]); ?>

                <fieldset class="search-task__categories">
                    <legend>Категории</legend>

                    <?php foreach ($modelCategoriesFilter->attributeLabels() as $key => $label): ?>
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
                    <?php foreach ($modelTasksFilter->checkboxesLabels() as $key => $value) {
                        echo $form->field($modelTasksFilter, $key, ['template' => '{input}{label}']
                        )->checkbox(
                            ['class' => 'visually-hidden checkbox__input', 'id' => $key],
                            false)->label($value, ['for' => $key, 'class' => false]);
                    } ?>
                </fieldset>

                <?php foreach ($modelTasksFilter->checkboxesLabels() as $key => $value) {
                    $form->field($modelTasksFilter, $key, ['template' => '{input}{label}']
                    )->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'id' => $key],
                        false)->label($value, ['for' => $key, 'class' => false]);
                } ?>

                <?= $form->field($modelTasksFilter, 'timeInterval', [
                    'template' => '{label}{input}',
                    'labelOptions' => ['tag' => false],
                ])->label($modelTasksFilter->attributeLabels()['timeInterval'], ['for' => 'timeInterval', 'class' => 'search-task__name'])
                    ->dropDownList($modelTasksFilter::getIntervalList()
                        , ['class' => "multiple-select input", 'id' => 'sa']);
                ?>

                <?= $form->field($modelTasksFilter, 'searchName', ['template' => '{label}{input}'])
                    ->input('search',
                        ['class' => "input-middle input"])
                    ->label($modelTasksFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]); ?>

                <?= Html::submitButton('Искать', ['class' => 'button']) ?>
                <?php ActiveForm::end(); ?>

            </div>
        </section>

    </div>
</main>
