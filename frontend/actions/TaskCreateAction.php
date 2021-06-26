<?php

namespace frontend\actions;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\forms\CreateTaskForm;
use TaskForce\Exception\FileException;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

class TaskCreateAction extends Action
{
    /**
     * @return string
     * @throws FileException
     */
    public function run()
    {
        $userId = Yii::$app->user->getId();
        $createTaskForm = new CreateTaskForm();

        if ($request = Yii::$app->request->post()) {
            $createTaskForm->load($request);
            $createTaskForm->files = UploadedFile::getInstances(model: $createTaskForm, attribute: 'files');
            if ($createTaskForm->validate()) {
                $taskId = $createTaskForm->saveData($userId);
                if ($taskId) {
                    $this->controller->redirect(url: 'tasks/view' . '/' . $taskId);
                } else {
                    $createTaskForm->addError(attribute: '', error:'Задача не создана, попробуйте позже.');
                }
            }
        }

        $categories = Category::all();
        $cities = City::getList();

        return $this->controller->render('create', compact('createTaskForm', 'categories', 'cities'));
    }
}