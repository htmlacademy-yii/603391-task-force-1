<?php

namespace frontend\actions;

use frontend\models\City;
use frontend\models\forms\AccountForm;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\NotificationsFilterForm;
use frontend\models\Specialization;
use frontend\models\User;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class AccountIndexAction extends Action
{

    /**
     * @return string|Response
     * @throws FileException
     * @throws TaskForceException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function run()
    {
        {
            $modelAccountForm = new AccountForm();
            $modelCategoriesForm = new CategoriesFilterForm();
            $modelNotificationsForm = new NotificationsFilterForm();

            if ($post = Yii::$app->request->post()) {
                $modelAccountForm->load($post, formName: 'AccountForm');
                $modelCategoriesForm->load($post, formName: 'CategoriesFilterForm');
                $modelNotificationsForm->load($post, formName: 'NotificationsFilterForm');
                $modelAccountForm->avatarFile = UploadedFile::getInstance(
                    model: $modelAccountForm,
                    attribute: 'avatarFile'
                );

                if ($modelAccountForm->validate() && $modelAccountForm->saveData()) {
                    Specialization::saveData($modelCategoriesForm);
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