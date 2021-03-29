<?php

namespace frontend\actions;

use Exception;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use Yii;
use yii\base\Action;
use yii\data\Pagination;

/**
 * Users list
 */
class UsersIndexAction extends Action
{
    /**
     * @param string $sortType
     * @return string
     * @throws TaskForceException
     * @throws Exception
     */
    public function run(string $sortType = ''): string
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

        $modelsUsers = User::findNewExecutors(request: $filterRequest,sortType: $sortType);

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
                $modelsUsers[$key]['categories'] = Specialization::findItemsByProfileId($element['profile_id']);
                $modelsUsers[$key]['countTasks'] = Task::findCountByUserId($element['id']);
                $modelsUsers[$key]['countReplies'] = Opinion::findCountOpinionsByUserId($element['id']);
                $modelsUsers[$key]['afterTime'] = Declination::getTimeAfter($element['date_login']);
            }
        }

        return $this->controller->render(
            view: 'index',
            params: compact(
                'modelsUsers',
                'sortType',
                'modelUsersFilter',
                'modelCategoriesFilter',
                'pagination'
            )
        );
    }
}