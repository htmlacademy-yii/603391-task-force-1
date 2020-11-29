<?php

namespace frontend\controllers;

use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\User;
use frontend\models\Work;
use TaskForce\Constant\UserRole;
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
    const MAX_PAGE_USERS = 5;

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

        if (($ids = Yii::$app->request->get()) && isset($ids['category'])) {
            $modelCategoriesFilter->setOneCategory($ids['category']);
            $filterRequest['CategoriesFilterForm']['categories'] = $modelCategoriesFilter->getCategoriesState();
        }

        if ($post = Yii::$app->request->post() && $modelUsersFilter->validate()) {
            $modelUsersFilter->load($post);
            $modelCategoriesFilter->updateProperties(
                $post['CategoriesFilterForm']['categories']
            );
            $filterRequest = ($post);

            if (strlen($filterRequest['UsersFilterForm']['searchName']) > 0) {
                $modelCategoriesFilter->init();
                $modelUsersFilter = new UsersFilterForm();
            }
        }

        $modelsUsers = User::findNewExecutors($filterRequest, $sortType);
        $pagination = new Pagination(
            [
                'totalCount' => $modelsUsers->count(),
                'pageSize' => self::MAX_PAGE_USERS,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]
        );

        $modelsUsers = $modelsUsers->offset($pagination->offset)->limit($pagination->limit)->all();

        if (!empty($modelsUsers)) {
            foreach ($modelsUsers as $key => $element) {
                $modelsUsers[$key]['categories'] = Specialization::findItemsByProfileId($element['profile_id']);
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
        $modelUser = Profile::findByUserId($id);
        if ($modelUser['role'] !== UserRole::EXECUTOR) {
            throw new NotFoundHttpException('Executor profile not found.');
        }

        $modelUser['countTask'] = Task::findCountTasksByUserId($id);
        $modelsOpinions = Opinion::findOpinionsByUserId($id);
        $countOpinions = Opinion::findCountOpinionsByUserId($id);
        $specializations = Specialization::findItemsByProfileId($id);
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
