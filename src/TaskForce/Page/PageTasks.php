<?php

namespace TaskForce\Page;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use frontend\models\Task;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use Yii;
use yii\data\Pagination;
use yii\web\Request;

class PageTasks
{
    private ?Pagination $pagination;
    private ?Request $request;
    private array $filterRequest = [];
    private TasksFilterForm $modelTasksFilter;
    private CategoriesFilterForm $modelCategoriesFilter;
    private $modelsTasks;

    public function __construct($request)
    {
        $this->request = $request;
        $this->modelTasksFilter = new TasksFilterForm();
        $this->modelCategoriesFilter = new CategoriesFilterForm();
        $this->modelCategoriesFilter->init();
    }

    /** Page initialisation.
     * @throws TaskForceException
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
        if ($post = $this->request->post()) {
            $this->modelTasksFilter->load($post);
            $this->modelCategoriesFilter->updateProperties(
                ($post)['CategoriesFilterForm']['categories']
            );
            $this->filterRequest = (array)$post;
        }

        if (Yii::$app->request->getIsGet()) {
            $ids = Yii::$app->request->get();
            if (isset($ids['category'])) {
                $this->modelCategoriesFilter->setOneCategory($ids['category']);
                $this->filterRequest['CategoriesFilterForm']['categories'] = $this->modelCategoriesFilter->getCategoriesState();
            }
        }
    }

    /**
     * Handle Request
     * @throws TaskForceException
     */
    protected function handleModel()
    {
        $this->modelsTasks = Task::findNewTask($this->filterRequest);
        $this->pagination = new Pagination(
            [
                'totalCount' => $this->modelsTasks->count(),
                'pageSize' => Yii::$app->params['maxPaginatorItems'],
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]
        );
        $this->modelsTasks = $this->modelsTasks->offset($this->pagination->offset)
            ->limit($this->pagination->limit)->all();
    }

    /** Add data to model
     * @throws TaskForceException
     */
    protected function addModelData() {
        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Declination::getTimeAfter($element['date_add']);
            }
        }
    }

    /**
     * Get Data to View
     */
    public function getPageData(): array
    {
        return ['modelsTasks' => $this->modelsTasks, 'modelTasksFilter'=> $this->modelTasksFilter,
            'modelCategoriesFilter'=>$this->modelCategoriesFilter, 'pagination'=>$this->pagination];
    }
}