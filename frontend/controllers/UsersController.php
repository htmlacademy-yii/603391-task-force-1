<?php

namespace frontend\controllers;

use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\Work;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use Yii;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use frontend\models\Profile;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class UsersController extends SecureController
{
    /**
     * @param string $sortType
     * @return string
     * @throws TaskForceException
     */
    public function actionIndex(string $sortType = ''): string
    {
        $filterRequest = [];
        $modelCategoriesFilter = new CategoriesFilterForm();
        $modelCategoriesFilter->init();
        $modelUsersFilter = new UsersFilterForm();

        if (Yii::$app->request->getIsGet()) {
            $ids = Yii::$app->request->get();
            if   (isset($ids['category'])) {
                $modelCategoriesFilter->setOneCategory($ids['category']);
                $filterRequest['CategoriesFilterForm']['categories']=$modelCategoriesFilter->getCategoriesState();
            }
        }

        if (Yii::$app->request->getIsPost()) {
            $modelUsersFilter->load(Yii::$app->request->post());
            $modelCategoriesFilter->updateProperties(
                (Yii::$app->request->post())['CategoriesFilterForm']['categories']
            );

            $filterRequest = (Yii::$app->request->post());

            if (strlen($filterRequest['UsersFilterForm']['searchName']) > 0) {
                $modelCategoriesFilter->init();
                $modelUsersFilter = new UsersFilterForm();
            }
        }

        $modelsUsers = Profile::findNewExecutors($filterRequest, $sortType);

        $pagination = new Pagination(
            [
                'totalCount' => $modelsUsers->count(),
                'pageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]
        );

        $modelsUsers = $modelsUsers->offset($pagination->offset)->limit($pagination->limit)->all();

        if (!empty($modelsUsers)) {
            foreach ($modelsUsers as $key => $element) {
                $modelsUsers[$key]['categories'] = Specialization::findSpecializationByUserId($element['id']);
                $modelsUsers[$key]['countTasks'] = Task::findCountTasksByUserId($element['id']);
                $modelsUsers[$key]['countReplies'] = Opinion::findCountOpinionsByUserId($element['id']);
                $modelsUsers[$key]['afterTime'] = Declination::getTimeAfter($element['date_login']);
            }
        }

        return $this->render(
            'index',
            compact(
                'modelsUsers',
                'sortType',
                'modelUsersFilter',
                'modelCategoriesFilter',
                'pagination'
            )
        );
    }

    /**
     * User view by $id
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws TaskForceException
     */
    public function actionView(int $id): string
    {
        $modelUser = Profile::findProfileByUserId($id);
        if ($modelUser['role'] !== \TaskForce\Task::ROLE_EXECUTOR) {
            throw new NotFoundHttpException('Профиль исполнителя не найден.');
        }

        $modelUser['countTask'] = Task::findCountTasksByUserId($id);
        $modelsOpinions = Opinion::findOpinionsByUserId($id);
        $countOpinions = Opinion::findCountOpinionsByUserId($id);
        $specializations = Specialization::findSpecializationByUserId($id);
        $works = Work::findWorkFilesByUserId($id);

        return $this->render(
            'view',
            compact(
                'modelUser',
                'modelsOpinions',
                'specializations',
                'countOpinions',
                'works'
            )
        );
    }

}
