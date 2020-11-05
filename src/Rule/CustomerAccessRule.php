<?php

namespace TaskForce\Rule;

use TaskForce\Constant\UserRole;
use Yii;
use yii\filters\AccessRule;

class CustomerAccessRule extends AccessRule
{
    public $allow = true;
    public $roles = ['@'];

    public function allows($action, $user, $request)
    {
        $parentRes = parent::allows($action, $user, $request);
        if ($parentRes !== true) {
            return $parentRes;
        }

        $role = Yii::$app->user->identity->role;
        return ($role === UserRole::CUSTOMER);
    }
}
