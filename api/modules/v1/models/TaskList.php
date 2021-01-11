<?php

namespace api\modules\v1\models;

use frontend\models\Chat;
use frontend\models\Task;
use frontend\models\User;

class TaskList extends Task
{
    public function fields()
    {
        return [
            'title' => 'name',
            'published_at' => 'date_add',
            'new_messages' =>  function () {
                return Chat::find($this->id)->count();
            },
            'author_name'=>function () {
                return User::findOne($this->customer_id)->name;
            },
            'id'
        ];
    }
}