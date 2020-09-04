<?php


namespace frontend\controllers;

use Yii;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use frontend\models\Profile;
use yii\web\Controller;

class UsersController extends Controller
{

    /**
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $filterRequest = [];
        $modelCategoriesFilter = new CategoriesFilterForm();
        $modelCategoriesFilter->init();
        $modelUsersFilter = new UsersFilterForm();

        if (Yii::$app->request->getIsPost()) {
            $modelUsersFilter->load(Yii::$app->request->post());
            $modelCategoriesFilter->updateProperties((Yii::$app->request->post())['CategoriesFilterForm']['categories']);
            $filterRequest = (Yii::$app->request->post());
        }

        $modelsUsers = Profile::findNewExecutors($filterRequest ?? []);

        return $this->render('index', [
            'models' => $modelsUsers ?? [],
            'modelUsersFilter' => $modelUsersFilter,
            'modelCategoriesFilter' => $modelCategoriesFilter,
        ]);


    }
}
