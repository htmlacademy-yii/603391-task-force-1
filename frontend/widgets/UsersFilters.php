<?php

namespace frontend\widgets;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class UsersFilters extends Widget
{
    public CategoriesFilterForm $modelCategoriesFilter;
    public UsersFilterForm $modelUsersFilter;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->modelCategoriesFilter && $this->modelUsersFilter) {
            return true;
        }

        return false;
    }

    /**
     * Registers
     */
    public function init(): bool
    {
        parent::init();
        if (!$this->validate()) {
            return false;
        }

        return true;
    }

    /**
     * Map html block
     * @return string|null
     */
    public function run()
    {
        if (!$this->validate()) {
            return null;
        }

        echo '<section class="search-task">
            <div class="search-task__wrapper">';
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
        );

        echo '<fieldset class="search-task__categories">
                    <legend>Категории</legend>';

        foreach ($this->modelCategoriesFilter->attributeLabels() as $key => $label) {
            echo $form
                ->field(
                    $this->modelCategoriesFilter,
                    sprintf('categories[%s]', $key),
                    [
                        'template' => '{input}{label}'
                    ]
                )
                ->checkbox(
                    [
                        'class' => 'visually-hidden checkbox__input',
                        'id' => $key,
                        'checked' => (bool)$this->modelCategoriesFilter[$key]
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
        }

        echo '</fieldset>
                <fieldset class="search-task__categories">
                    <legend>Дополнительно</legend>';

        foreach ($this->modelUsersFilter->checkboxesLabels() as $key => $value) {
            echo $form->field(
                $this->modelUsersFilter,
                $key,
                ['template' => '{input}{label}']
            )->checkbox(
                ['class' => 'visually-hidden checkbox__input', 'id' => $key],
                false
            )->label($value, ['for' => $key, 'class' => false]);
        };
        echo '</fieldset>';

        echo $form->field($this->modelUsersFilter, 'searchName', ['template' => '{label}{input}'])
            ->input(
                'search',
                ['class' => "input-middle input"]
            )
            ->label($this->modelUsersFilter->attributeLabels()['searchName'], ['class' => "search-task__name"]);

        echo Html::submitButton('Искать', ['class' => 'button']);
        ActiveForm::end();
        echo '</div>
        </section>';
    }
}