<?php

namespace frontend\actions;

use frontend\models\City;
use frontend\models\forms\AccountForm;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\NotificationsFilterForm;
use frontend\models\User;
use Yii;
use yii\base\Action;

class AccountIndexAction extends Action
{
    public function run()
    {
        {
            $modelAccountForm = new AccountForm();
            $modelCategoriesForm = new CategoriesFilterForm();
            $modelNotificationsForm = new NotificationsFilterForm();

            if ($post = Yii::$app->request->post()) {
                $modelAccountForm->load($post,'AccountForm');
                $modelCategoriesForm->load($post,'CategoriesFilterForm');
                $modelNotificationsForm->load($post,'NotificationsFilterForm');

                if ($modelAccountForm->validate() && $modelAccountForm->saveData()) {
                    $modelCategoriesForm->saveData();
                    $modelNotificationsForm->saveData();
                    User::updateUserRoleBySpecialisations();
                    return $this->controller->goHome();
                }
            } else {
                $modelAccountForm->init();
                $modelCategoriesForm->loadSpec();
                $modelNotificationsForm->loadNotify();
            }

            $cities = City::getList();

            return $this->controller->render(
                'index',
                compact(
                    'modelAccountForm',
                    'cities',
                    'modelCategoriesForm',
                    'modelNotificationsForm'
                )
            );
        }
    }
}