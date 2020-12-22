<?php

namespace TaskForce\widgets;

use Yii;
use yii\base\Widget;

class ChangeAvatarWidget extends Widget
{
    public function run(): ?string
    {
        parent::run();
        $loggedUser = Yii::$app->user->identity;
        $userAvatar = $loggedUser->getProfiles()->asArray()->one()['avatar'] ?? 'no-avatar.jpg';
        return $this->render('../account/view', ['loggedUser'=>$loggedUser, 'userAvatar'=>$userAvatar]);
    }
}