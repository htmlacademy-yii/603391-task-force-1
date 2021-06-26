<?php

namespace api\modules\v1\models;

use frontend\models\Event;
use frontend\models\Task;

class TaskList extends Task
{
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'title' => 'name',
            'published_at' => 'date_add',
            'new_messages' => function () {
                return Event::findNewMessagesByTask($this->id);
            },
            'author_name' => function () {
                return $this->customer->name;
            },
            'id'
        ];
    }
}