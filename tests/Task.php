<?php

use app\AvailableActions;

// Активация утверждений
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_CALLBACK, 'dcb_callback');

// Создание обработчика
function my_assert_handler($file, $line, $code, $desc = null) {
    if ($desc) {
        echo "$desc - ";
    } echo "FALSE строка $line ";
}

// Подключение callback-функции
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

// Тестирование функции getNextStatus
$action1 = new AvailableActions(1,1,'10.11.2019');
assert($action1->getNextStatus(AvailableActions::ACTIONS['CANCEL'], AvailableActions::ROLES['EXECUTOR']) !== AvailableActions::STATUSES['CANCEL'], 'Действие "Отмена", Статусы Новое -> Отменено, Роль Исполнитель');
assert($action1->getNextStatus(AvailableActions::ACTIONS['CANCEL'], AvailableActions::ROLES['CONSUMER']) == AvailableActions::STATUSES['CANCEL'], 'Действие "Отмена", Статусы Новое -> Отменено, Роль Заказчик');
assert($action1->getNextStatus(AvailableActions::ACTIONS['ASSIGN'], AvailableActions::ROLES['EXECUTOR']) !== AvailableActions::STATUSES['IN_WORK'], 'Действие "Назначить", Статусы Новое -> В_работе, Роль Исполнитель');
assert($action1->getNextStatus(AvailableActions::ACTIONS['ASSIGN'], AvailableActions::ROLES['CONSUMER']) == AvailableActions::STATUSES['IN_WORK'], 'Действие "Назначить", Статусы Новое -> В_работе, Роль Заказчик');


$action1 = new AvailableActions(1,1,'10.11.2019', AvailableActions::STATUSES['IN_WORK']);
assert($action1->getNextStatus(AvailableActions::ACTIONS['DONE'], AvailableActions::ROLES['EXECUTOR']) !== AvailableActions::STATUSES['DONE'], 'Действие "Выполнено", Статусы В_работе -> Выполнено, Роль Исполнитель');
assert($action1->getNextStatus(AvailableActions::ACTIONS['DONE'], AvailableActions::ROLES['CONSUMER']) == AvailableActions::STATUSES['DONE'], 'Действие "Выполнено", Статусы В_работе -> Выполнено, Роль Заказчик');
assert($action1->getNextStatus(AvailableActions::ACTIONS['REFUSE'], AvailableActions::ROLES['EXECUTOR']) == AvailableActions::STATUSES['FAILED'], 'Действие "Отказаться", Статусы В_работе -> Провалено, Роль Исполнитель');
assert($action1->getNextStatus(AvailableActions::ACTIONS['REFUSE'], AvailableActions::ROLES['CONSUMER']) !== AvailableActions::STATUSES['FAILED'], 'Действие "Отказаться", Статусы В_работе -> Провалено, Роль Заказчик');



