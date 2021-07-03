<?php

namespace TaskForce\widgets;

use frontend\models\Work;
use yii\base\Widget;

class PhotosListWidget extends Widget
{
    public int $userId;

    /**
     * @return string|null
     */
    public function run()
    {
        parent::run();
        $works = Work::find()->where(['user_id'=>$this->userId])->asArray()->all();

        return $this->render('@widgets/photos/view', ['works'=> $works, 'userId'=>$this->userId]);
    }
}