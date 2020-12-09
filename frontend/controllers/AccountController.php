<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\forms\AccountForm;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\NotificationsFilterForm;
use Yii;

class AccountController extends SecureController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $modelAccountForm = new AccountForm();
        $modelCategoriesForm = new CategoriesFilterForm();
        $modelNotificationsForm = new NotificationsFilterForm();

        if ($post = Yii::$app->request->post()) {
            $modelAccountForm->load($post);

            if ($modelAccountForm->validate() && $modelAccountForm->saveData()) {
                return $this->goHome();
            }

        } else {
            $modelAccountForm->init();
            $modelCategoriesForm->loadSpec();
            $modelNotificationsForm->init(false);
        }

        $cities = City::getList();

        return $this->render(
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
