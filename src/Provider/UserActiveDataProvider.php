<?php

namespace TaskForce\Provider;

use frontend\models\Opinion;
use frontend\models\Profile;
use frontend\models\Specialization;
use frontend\models\Task;
use TaskForce\Helpers\Declination;
use yii\data\ActiveDataProvider;

class UserActiveDataProvider extends ActiveDataProvider
{
    /**
     * @var string|callable имя столбца с ключом или callback-функция, возвращающие его
     */
    public $key;

    /**
     * {@inheritdoc}
     * @throws \TaskForce\Exception\TaskForceException
     * @throws \Exception
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();

        foreach ($models as $key => $model) {
            $models[$key]['categories'] = Specialization::findItemsByProfileId(
                Profile::findByUserId($model['id'])['profile_id']
            );
            $models[$key]['countTasks'] = Task::findCountByUserId($model['id']);
            $models[$key]['countReplies'] = Opinion::findCountOpinionsByUserId($model['id']);
            $models[$key]['afterTime'] = Declination::getTimeAfter($model['date_login']);
        }

        return $models;
    }
}