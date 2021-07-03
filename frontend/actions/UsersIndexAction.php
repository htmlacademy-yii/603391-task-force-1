<?php

namespace frontend\actions;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use Yii;
use yii\base\Action;

/**
 * Users list
 */
class UsersIndexAction extends Action
{
    /**
     * @param string $sortType
     * @return string
     */
    public function run(string $sortType = '')
    {
        $modelCategoriesFilter = new CategoriesFilterForm();
        $modelCategoriesFilter->init();
        $modelUsersFilter = new UsersFilterForm();
        $getRequest = Yii::$app->request->get();

        if (isset($getRequest['category'])) {
                $modelCategoriesFilter->setOneCategory($getRequest['category']);
          }

        if ($postRequest = yii::$app->request->post()) {
            $modelCategoriesFilter->updateProperties(
                ($postRequest)['CategoriesFilterForm']['categories']
            );
            $dataProvider = $modelUsersFilter->search($postRequest);
        } else {
            $dataProvider = $modelUsersFilter->search($getRequest);
        }

        return $this->controller->render(
            view: 'index',
            params: compact(
                'sortType',
                'modelUsersFilter',
                'modelCategoriesFilter',
                'dataProvider',
            )
        );
    }
}