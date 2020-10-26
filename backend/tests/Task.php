<?php
require "../vendor/autoload.php";
define('DEBUG_MODE', true);

use TaskForce\Task;

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
$action1 = new Task(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), Task::STATUS_NEW);
assert($action1->getNextStatus(Task::ACTION_CANCEL, Task::ROLE_CUSTOMER) === Task::STATUS_CANCEL);
assert($action1->getNextStatus(Task::ACTION_ASSIGN, Task::ROLE_CUSTOMER) === Task::STATUS_IN_WORK);
assert($action1->getNextStatus(Task::ACTION_RESPOND, Task::ROLE_EXECUTOR) === Task::STATUS_IN_WORK);

$action1 = new Task(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), Task::STATUS_IN_WORK);
assert($action1->getNextStatus(Task::ACTION_REFUSE, Task::ROLE_EXECUTOR) === Task::STATUS_FAILED);
assert($action1->getNextStatus(Task::ACTION_COMPLETE, Task::ROLE_CUSTOMER) === Task::STATUS_COMPLETE);

// проверяем метод получения возможных действий
$action1 = new Task(1, 2, new DateTime('2019-11-06 21:00:00 EDT'), Task::STATUS_NEW);
assert($action1->getAvailableActions(1) === ['TaskForce\Actions\ResponseAction']);
assert($action1->getAvailableActions(2) === ['TaskForce\Actions\CancelAction', 'TaskForce\Actions\AssignAction']);

