<?php

namespace TaskForce\widgets;

use frontend\models\Work;
use yii\base\Widget;

class PhotosWidget extends Widget
{

    public int $userId;

    public function run(): ?string
    {
        parent::run();
        $works = Work::find()->where(['user_id'=>$this->userId])->asArray()->all();

        return $this->render('../photos/view', ['works'=> $works, 'userId'=>$this->userId]);
    }

}