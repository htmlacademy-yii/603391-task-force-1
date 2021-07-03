<?php

namespace TaskForce\widgets;

use Yii;
use yii\base\Widget;

class AccountWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        $loggedUser = Yii::$app->user->identity;
        $userAvatar = $loggedUser->getProfiles()->asArray()->one()['avatar'] ?? 'no-avatar.jpg';
        return $this->render('@widgets/account/view',
                             ['loggedUser'=>$loggedUser, 'userAvatar'=>$userAvatar]);
    }
}