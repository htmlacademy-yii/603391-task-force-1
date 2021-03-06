<?php

namespace frontend\controllers;

use frontend\models\Favorite;
use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\Work;
use TaskForce\Constant\UserRole;
use TaskForce\Exception\TaskForceException;
use Throwable;
use Yii;
use frontend\models\Profile;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends SecureController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'frontend\actions\UsersIndexAction',
            ],
        ];
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
        $currentUserId = Yii::$app->user->getId();
        $modelUser = Profile::findByUserId($id);
        if ($modelUser['role'] !== UserRole::EXECUTOR) {
            throw new NotFoundHttpException('Executor profile not found.');
        }

        $modelUser['countTask'] = Task::findCountByUserId($id);
        $modelUser['favorite'] = (bool)Favorite::findOne(['favorite_id' => $id, 'user_id' => $currentUserId]);
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

    /**
     * @param int $userId
     * @return \yii\web\Response
     * @throws TaskForceException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionBookmark(int $userId): Response
    {
        $currentUserId = Yii::$app->user->getId();
        if (!$userId) {
            throw new TaskForceException('Не задан параметр userId.');
        }
        $favorite = Favorite::findOne(['favorite_id' => $userId, 'user_id' => $currentUserId]);

        if ($favorite) {
            $favorite->delete();
        } else {
            $favorite = new Favorite();
            $favorite->user_id = Yii::$app->user->getId();
            $favorite->favorite_id = $userId;
            $favorite->save();
        }

        return $this->redirect(['users/view', 'id' => $userId]);
    }
}
