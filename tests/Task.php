<?php

use TaskForce\Task;

// Активация утверждений
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 1);

// Создание обработчика
function my_assert_handler($file, $line, $code, $desc = null) {
    if ($desc) {
        echo "$desc - ";
    } echo "line $line ".PHP_EOL;
}

// Подключение callback-функции
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

// Тестирование метода cancel()
$action1 = new Task(1,1, new DateTime('2019-11-06 21:00:00 EDT'));
$action1->cancel(Task::ROLE_CONSUMER);
//$action1->cancel(Task::ROLE_EXECUTOR);


$action1 = new Task(1,1, new DateTime('2019-11-06 21:00:00 EDT'));
$action1->assign(Task::ROLE_CONSUMER);
//$action1->assign(Task::ROLE_EXECUTOR);

$action1 = new Task(1,1, new DateTime('2019-11-06 21:00:00 EDT'));
//assert($action1->getNextStatus(Task::ACTION_CANCEL, Task::ROLE_CONSUMER) == Task::STATUS_CANCEL, 'Действие "Отмена", Статусы Новое -> Отменено, Роль Заказчик');
//assert($action1->getNextStatus(Task::ACTION_ASSIGN, Task::ROLE_EXECUTOR) !== Task::STATUS_IN_WORK, 'Действие "Назначить", Статусы Новое -> В_работе, Роль Исполнитель');
//assert($action1->getNextStatus(Task::ACTION_ASSIGN, Task::ROLES_CONSUMER) == Task::STATUS_IN_WORK, 'Действие "Назначить", Статусы Новое -> В_работе, Роль Заказчик');





