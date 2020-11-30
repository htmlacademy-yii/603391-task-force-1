<?php

namespace TaskForce\Page;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\UsersFilterForm;
use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\Helpers\Declination;
use Yii;
use yii\web\Request;
use yii\data\Pagination;

class UsersPage
{
    /**
     * @var mixed
     */
    private $modelsUsers;
    private UsersFilterForm $modelUsersFilter;
    private CategoriesFilterForm $modelCategoriesFilter;
    private ?Request $request;
    private string $sortType = '';
    private array $filterRequest = [];
    private ?Pagination $pagination;

    /**
     * UsersPage constructor.
     * @param Request|\yii\web\Request $request
     * @param string $sortType
     */
    public function __construct($request, string $sortType)
    {
        $this->request = $request;
        $this->sortType = $sortType;
        $this->modelCategoriesFilter = new CategoriesFilterForm();
        $this->modelCategoriesFilter->init();
        $this->modelUsersFilter = new UsersFilterForm();
    }

    /** UsersPage initialisation.
     */
    public function init()
    {
        $this->handleRequest();
        $this->handleModel();
        $this->addModelData();
        $this->getPageData();
    }

    /**
     * Handle Request
     */
    private function handleRequest()
    {
        if (($ids = $this->request->get()) && isset($ids['category'])) {
            $this->modelCategoriesFilter->setOneCategory($ids['category']);
            $this->filterRequest['CategoriesFilterForm']['categories'] = $this->modelCategoriesFilter->getCategoriesState(
            );
        }
        if ($post = $this->request->post()) {
            $this->modelUsersFilter->load($post);
            $this->modelCategoriesFilter->updateProperties(
                $post['CategoriesFilterForm']['categories']
            );
            $this->filterRequest = (array)$post;
            if (strlen($this->filterRequest['UsersFilterForm']['searchName']) > 0) {
                $this->modelCategoriesFilter->init();
                $this->modelUsersFilter = new UsersFilterForm();
            }
        }
    }

    private function handleModel()
    {
        $this->modelsUsers = User::findNewExecutors($this->filterRequest, $this->sortType);
        $this->pagination = new Pagination(
            [
                'totalCount' => $this->modelsUsers->count(),
                'pageSize' => Yii::$app->params['maxPaginatorItems'],
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]
        );
        $this->modelsUsers = $this->modelsUsers
            ->offset($this->pagination->offset)
            ->limit($this->pagination->limit)
            ->all();
    }

    private function addModelData()
    {
        if (!empty($this->modelsUsers)) {
            foreach ($this->modelsUsers as $key => $element) {
                $this->modelsUsers[$key]['categories'] = Specialization::findItemsByProfileId($element['profile_id']);
                $this->modelsUsers[$key]['countTasks'] = Task::findCountTasksByUserId($element['id']);
                $this->modelsUsers[$key]['countReplies'] = Opinion::findCountOpinionsByUserId($element['id']);
                $this->modelsUsers[$key]['afterTime'] = Declination::getTimeAfter($element['date_login']);
            }
        }
    }

    public function getPageData(): array
    {
        return [
            'modelsUsers' => $this->modelsUsers,
            'sortType' => $this->sortType,
            'modelUsersFilter' => $this->modelUsersFilter,
            'modelCategoriesFilter' => $this->modelCategoriesFilter,
            'pagination' => $this->pagination,
        ];
    }
}