<?php


namespace frontend\models\forms;

use yii\base\Model;

class CompleteTaskForm extends Model
{

    const VALUE_YES = 'yes';
    const VALUE_DIFFICULTIES = 'difficulties';

    const VALUES = [
        self::VALUE_YES => 'да',
        self::VALUE_DIFFICULTIES => 'Возникли проблемы'
    ];

    public $completion =  null;

    public $comment = null;
    public $rating = null;



    public function rules()
    {
        return [
            ['completion', 'required', 'skipOnEmpty' => false],
            [
                'completion',
                'in',
                'range' => array_keys(self::VALUES),
            ],
            [['comment'], 'trim'],
            ['rating', 'required'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5]
        ];
    }

    public function attributeLabels()
    {
        return [
            'completion' => 'Задание выполнено?',
            'comment' => 'Комментарий',
            'rating' => 'Оценка'
        ];
    }



}
