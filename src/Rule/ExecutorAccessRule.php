<?php

namespace TaskForce\Rule;

use TaskForce\Constant\UserRole;
use Yii;
use yii\filters\AccessRule;

class ExecutorAccessRule extends AccessRule
{
    public $allow = true;
    public $roles = ['@'];

    /**
     * @param \yii\base\Action $action
     * @param false|\yii\web\User $user
     * @param \yii\web\Request $request
     * @return bool|null
     */
    public function allows($action, $user, $request)
    {
        $role = Yii::$app->user->identity->role;
        $parentRes = parent::allows($action, $user, $request);
        if ($parentRes !== true) {
            return $parentRes;
        }

        return ($role === UserRole::EXECUTOR);
    }
}
