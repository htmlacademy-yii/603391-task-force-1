<?php

/** @var CategoriesFilterForm $modelCategoriesFilter */
/** @var TasksFilterForm $modelTasksFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

const VISUALLY_HIDDEN_CHECKBOX_INPUT = 'visually-hidden checkbox__input';
const INPUT_LABEL = '<label class="checkbox__legend">{input}{hint}</label>';
?>

<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'search-task__form'
                ],
                'fieldConfig' => [
                    'options' => [
                        'tag' => false,
                    ],
                ],
            ]
        ); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?php
            foreach ($modelCategoriesFilter->attributeLabels() as $key => $label) {
                echo $form
                    ->field(
                        $modelCategoriesFilter,
                        sprintf('categories[%s]', $key),
                        [
                            'template' => INPUT_LABEL
                        ]
                    )
                    ->checkbox(
                        [
                            'class' => VISUALLY_HIDDEN_CHECKBOX_INPUT,
                            'id' => $key,
                            'checked' => (bool)$modelCategoriesFilter[$key]
                        ],
                        false
                    )
                    ->hint($label,['tag' => 'span', 'class' => false]) ;
            } ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?php
            foreach ($modelTasksFilter->checkboxesLabels() as $key => $value) {
                echo $form->field(
                    $modelTasksFilter,
                    $key,
                    ['template' => INPUT_LABEL]
                )->checkbox(
                    ['class' => VISUALLY_HIDDEN_CHECKBOX_INPUT, 'id' => $key],
                    false
                )->hint($value,['tag' => 'span', 'class' => false]) ;
            } ?>
        </fieldset>
        <?= $form->field(
            $modelTasksFilter,
            'timeInterval',
            [
                'template' => '{label}{input}',
                'labelOptions' => ['tag' => false],
            ]
        )->label(
            $modelTasksFilter->attributeLabels()['timeInterval'],
            ['for' => 'timeInterval', 'class' => 'search-task__name']
        )
            ->dropDownList(
                $modelTasksFilter::getIntervalList(),
                [
                    'class' => "multiple-select input",
                    'id' => 'sa',
                    'options'=>[ $modelTasksFilter->timeInterval => ['selected'=>true]]
                ]
            ); ?>

        <?= $form->field($modelTasksFilter, 'searchName', ['template' => '{label}{input}'])
            ->input(
                'search',
                ['class' => "input-middle input"]
            )
            ->label($modelTasksFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]); ?>
        <?php
        echo Html::submitButton('Искать', ['class' => 'button']);
        ActiveForm::end(); ?>
    </div>
</section>