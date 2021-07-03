<?php

/** @var UsersFilterForm $modelUsersFilter */
/** @var CategoriesFilterForm $modelCategoriesFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin(
            [
                'options' => [
                    'name' => 'users',
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
                            'template' => '<label class="checkbox__legend">{input}{hint}</label>'
                        ]
                    )
                    ->checkbox(
                        [
                            'class' => 'visually-hidden checkbox__input',
                            'id' => $key,
                            'checked' => (bool)$modelCategoriesFilter[$key]
                        ],
                        false
                    )->hint($label,['tag' => 'span', 'class' => false]) ;
            } ?>
        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>

            <?php
            foreach ($modelUsersFilter->checkboxesLabels() as $key => $value) {
                echo $form->field(
                    $modelUsersFilter,
                    $key,
                    ['template' => '<label class="checkbox__legend">{input}{hint}</label>']
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'id' => $key, 'checked' => (bool)$modelUsersFilter->{$key}],
                    false
                )->hint($value,['tag' => 'span', 'class' => false]) ;;
            } ?>
        </fieldset>
        <?php
        echo $form->field($modelUsersFilter, 'searchName', ['template' => '{label}{input}'])
            ->input(
                'search',
                ['class' => "input-middle input", 'value'=> strip_tags($modelUsersFilter->searchName)]
            )
            ->label($modelUsersFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]);

        echo Html::submitButton('Искать', ['class' => 'button']);
        ActiveForm::end();
        ?>
    </div>
</section>