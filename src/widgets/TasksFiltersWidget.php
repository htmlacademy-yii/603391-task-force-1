<?php

namespace TaskForce\widgets;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use yii\base\Widget;

class TasksFiltersWidget extends Widget
{
    public CategoriesFilterForm $modelCategoriesFilter;
    public TasksFilterForm $modelTasksFilter;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->modelCategoriesFilter && $this->modelTasksFilter) {
            return true;
        }

        return false;
    }

    /**
     * Map html block
     * @return string|null
     */
    public function run(): ?string
    {
        if (!$this->validate()) {
            return null;
        }

        return $this->render('@widgets/tasksFilters/view',
                             [
                                 'modelCategoriesFilter' => $this->modelCategoriesFilter,
                                 'modelTasksFilter' => $this->modelTasksFilter
                             ]
        );
    }
}