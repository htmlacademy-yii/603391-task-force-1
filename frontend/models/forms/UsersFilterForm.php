<?php

namespace frontend\models\forms;

use frontend\models\Opinion;
use frontend\models\Specialization;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\Helpers\Declination;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UsersFilterForm extends Model
{
    private const DEFAULT_MAX_ELEMENTS = 5;
    public bool $freeNow = false;
    public bool $onlineNow = false;
    public bool $feedbackExists = false;
    public bool $searchName = false;
    public bool $isFavorite = false;

    /**
     * @return array|string[]
     */
    public function checkboxesLabels(): array
    {
        return [
            'freeNow' => 'Сейчас свободен',
            'onlineNow' => 'Сейчас онлайн',
            'feedbackExists' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'freeNow' => 'Сейчас свободен',
            'onlineNow' => 'Сейчас онлайн',
            'feedbackExists' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @return array
     */
    public function fieldsLabels(): array
    {
        return [
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['freeNow', 'onlineNow', 'feedbackExists', 'isFavorite', 'searchName'], 'safe'],
            [['searchName'], 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9ё_\s,]+$/']
        ];
    }

    /**
     * @param $filterRequest
     * @param string $sortType
     * @return \yii\data\ActiveDataProvider
     */
    public function search(  $filterRequest, string $sortType = '') : ActiveDataProvider
    {
        $query = User::findNewExecutors(request: $filterRequest, sortType: $sortType);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'Pagination' => [
                    'pageSize' => Yii::$app->params['maxPaginatorItems'] ?? self::DEFAULT_MAX_ELEMENTS,
                ],
            ]
        );

        return $dataProvider;

    }

    /**
     * @throws \TaskForce\Exception\TaskForceException
     * @throws \Exception
     */
    public static function addFields(array $models): array
    {
        $updatedModels = [];
        foreach ($models as $model) {
            $model['categories'] = Specialization::findItemsByProfileId($model['profile_id']);
            $model['countTasks'] = Task::findCountByUserId($model['id']);
            $model['countReplies'] = Opinion::findCountOpinionsByUserId($model['id']);
            $model['afterTime'] = Declination::getTimeAfter($model['date_login']);
            $updatedModels[] = $model;
         }

        return $updatedModels;
    }
}
