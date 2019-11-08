<?php
require  "../vendor/autoload.php";
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

$action1 = new Task(1, 1, new DateTime('2019-11-06 21:00:00 EDT'), Task::STATUS_NEW);
assert($action1->getNextStatus(Task::ACTION_CANCEL, Task::ROLE_CONSUMER) === 'Cancel');
assert($action1->getNextStatus(Task::ACTION_ASSIGN, Task::ROLE_CONSUMER) === 'In_work');
assert($action1->getNextStatus(Task::ACTION_RESPOND, Task::ROLE_EXECUTOR) === 'In_work');

$action1 = new Task(1, 1, new DateTime('2019-11-06 21:00:00 EDT'), Task::STATUS_IN_WORK);
assert($action1->getNextStatus(Task::ACTION_REFUSE, Task::ROLE_EXECUTOR) === 'Failed');
assert($action1->getNextStatus(Task::ACTION_DONE, Task::ROLE_CONSUMER) === 'Done');

