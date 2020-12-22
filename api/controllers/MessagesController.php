<?php

namespace frontend\modules\api\controllers;

use frontend\models\Chat;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Chat::class;
}