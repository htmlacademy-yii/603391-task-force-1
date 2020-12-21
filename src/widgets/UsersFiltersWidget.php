<?php

namespace TaskForce\widgets;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use yii\base\Widget;

class UsersFiltersWidget extends Widget
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
     * Map html block
     * @return string|null
     */
    public function run()
    {
        if (!$this->validate()) {
            return null;
        }

        return $this->render('@widgets/usersFilters/view',
                             [
                                 'modelCategoriesFilter' => $this->modelCategoriesFilter,
                                 'modelUsersFilter' => $this->modelUsersFilter
                             ]
        );
    }
}
