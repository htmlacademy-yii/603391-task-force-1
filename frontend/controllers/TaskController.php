<?php


namespace frontend\controllers;


use frontend\models\Category;
use frontend\models\forms\CreateTaskForm;
use frontend\models\Profile;
use TaskForce\Exception\FileException;
use TaskForce\Task;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class TaskController extends SecureController
{
    private const HTTP_STATUS_403 = 403;

    /**
     * @return string
     * @throws HttpException
     * @throws FileException
     */
    public function actionCreate(): string
    {
        $id = Yii::$app->user->getId();
        $role = Profile::findProfileByUserId($id)['role'];


        if ($role !== Task::ROLE_CUSTOMER) {
            throw new HttpException(self::HTTP_STATUS_403, 'Access denied.');
        };

        $categories = ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');
        $createTaskForm = new CreateTaskForm();

        if ($request = Yii::$app->request->post()) {
            $createTaskForm->load($request);
            $createTaskForm->files = UploadedFile::getInstances($createTaskForm, 'files');

            if (!$createTaskForm->validate()) {
                return $this->render('create', compact('createTaskForm', 'categories'));
            }
            $taskID = $createTaskForm->saveData($id);

            if ($taskID) {
                Yii::$app->session->setFlash('success', 'Задача создана');
                $this->redirect('../tasks/view/' . $taskID);
            }
        }


        return $this->render('create', compact('createTaskForm', 'categories'));
    }
}
