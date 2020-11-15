<?php

namespace frontend\models\forms;

use yii\base\Model;

class CompleteTaskForm extends Model
{
    public const VALUE_YES = 'yes';
    public const VALUE_DIFFICULTIES = 'difficult';

    public const VALUES = [
        self::VALUE_YES => 'Да',
        self::VALUE_DIFFICULTIES => 'Возникли проблемы'
    ];

    public ?string $completion = null;
    public ?string $comment = null;
    public ?int $rating = null;

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
