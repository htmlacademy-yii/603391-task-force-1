<?php
require "../vendor/autoload.php";
define('DEBUG_MODE', true);

use TaskForce\TaskEntity;

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 0);


function my_assert_handler($file, $line, $code, $desc = null)
{
    if ($desc) {
        echo "$desc - ";
    }
    echo " line $line <br/>";
}


assert_options(ASSERT_CALLBACK, 'my_assert_handler');

// проверяем метод получения следующего статуса
$action1 = new TaskEntity(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), TaskEntity::STATUS_NEW);
assert($action1->getNextStatus(TaskEntity::ACTION_CANCEL, TaskEntity::ROLE_CUSTOMER) === TaskEntity::STATUS_CANCEL);
assert($action1->getNextStatus(TaskEntity::ACTION_ASSIGN, TaskEntity::ROLE_CUSTOMER) === TaskEntity::STATUS_IN_WORK);
assert($action1->getNextStatus(TaskEntity::ACTION_RESPOND, TaskEntity::ROLE_EXECUTOR) === TaskEntity::STATUS_IN_WORK);

$action1 = new TaskEntity(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), TaskEntity::STATUS_IN_WORK);
assert($action1->getNextStatus(TaskEntity::ACTION_REFUSE, TaskEntity::ROLE_EXECUTOR) === TaskEntity::STATUS_FAILED);
assert($action1->getNextStatus(TaskEntity::ACTION_COMPLETE, TaskEntity::ROLE_CUSTOMER) === TaskEntity::STATUS_COMPLETE);

// проверяем метод получения возможных действий
$action1 = new TaskEntity(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), TaskEntity::STATUS_NEW);
assert($action1->getAvailableActions(1) === ['TaskForce\Actions\ResponseAction']);
assert($action1->getAvailableActions(2) === ['TaskForce\Actions\CancelAction', 'TaskForce\Actions\AssignAction']);

