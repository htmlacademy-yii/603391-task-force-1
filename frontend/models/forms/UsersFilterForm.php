<?php

namespace frontend\models\forms;

use yii\base\Model;

class UsersFilterForm extends Model
{

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
     * @return array|string[]
     */
    public function attributeLabels(): array
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
     * @return array|string[]
     */
    public function fieldsLabels(): array
    {
        return [
            'searchName' => 'Поиск по названию'
        ];
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            [['freeNow', 'onlineNow', 'feedbackExists', 'isFavorite', 'searchName'], 'safe'],
            [['searchName'], 'match', 'pattern' => '/^[A-Za-zА-Яа-я0-9ё_\s,]+$/']
        ];
    }

}
