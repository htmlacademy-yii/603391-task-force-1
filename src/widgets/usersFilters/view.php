<?php

/** @var UsersFilterForm $modelUsersFilter */

/** @var CategoriesFilterForm $modelCategoriesFilter */

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<section class="search-task">
    <div class="search-task__wrapper">';
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
                            'template' => '{input}{label}'
                        ]
                    )
                    ->checkbox(
                        [
                            'class' => 'visually-hidden checkbox__input',
                            'id' => $key,
                            'checked' => (bool)$modelCategoriesFilter[$key]
                        ],
                        false
                    )
                    ->label(
                        $label,
                        [
                            'for' => $key,
                            'class' => false,
                            'tag' => false
                        ]
                    );
            } ?>

        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>

            <?php
            foreach ($modelUsersFilter->checkboxesLabels() as $key => $value) {
                echo $form->field(
                    $modelUsersFilter,
                    $key,
                    ['template' => '{input}{label}']
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'id' => $key],
                    false
                )->label($value, ['for' => $key, 'class' => false]);
            } ?>
        </fieldset>
        <?php
        echo $form->field($modelUsersFilter, 'searchName', ['template' => '{label}{input}'])
            ->input(
                'search',
                ['class' => "input-middle input"]
            )
            ->label($modelUsersFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]);

        echo Html::submitButton('Искать', ['class' => 'button']);
        ActiveForm::end();
        ?>
    </div>
</section>