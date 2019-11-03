<?php


namespace app;


class AvailableActions
{
    const ROLES = [
        'CONSUMER' => 'Заказчик',
        'EXECUTOR' => 'Исполнитель'
    ];

    const ACTIONS = [
        'CANCEL' => 'Отменить',
        'ASSIGN' => 'Назначить',
        'DONE' => 'Выполнено',
        'REFUSE' => 'Отказаться',
        'RESPOND' => 'Откликнуться'
    ];

    const STATUSES = [
        'NEW' => 'Новое',
        'CANCEL' => 'Отменено',
        'IN_WORK' => 'В работе',
        'DONE' => 'Выполнено',
        'FAILED' => 'Провалено'
    ];

    // массив действий(переходов) из статуса в статус для определенных ролей
    const CONVERSIONS = [
        [
            'name' => self::ACTIONS['CANCEL'],
            'from' => self::STATUSES['NEW'], 'to' => self::STATUSES['CANCEL'], 'role' => self::ROLES['CONSUMER']
        ],
        [
            'name' => self::ACTIONS['RESPOND'],
            'from' => self::STATUSES['NEW'], 'to' => self::STATUSES['IN_WORK'], 'role' => self::ROLES['EXECUTOR']
        ],
        [
            'name' => self::ACTIONS['ASSIGN'],
            'from' => self::STATUSES['NEW'], 'to' => self::STATUSES['IN_WORK'], 'role' => self::ROLES['CONSUMER']
        ],
        [
            'name' => self::ACTIONS['DONE'],
            'from' => self::STATUSES['IN_WORK'], 'to' => self::STATUSES['DONE'], 'role' => self::ROLES['CONSUMER']
        ],
        [
            'name' => self::ACTIONS['REFUSE'],
            'from' => self::STATUSES['IN_WORK'], 'to' => self::STATUSES['FAILED'], 'role' => self::ROLES['EXECUTOR']
        ],
    ];

    private $performerID;
    private $customerID;
    private $activeStatus;
    private $deadLine;

    public function __construct($performerID, $customerID, $deadLine, $activeStatus = self::STATUSES['NEW'])
    {
        $this->performerID = $performerID;
        $this->customerID = $customerID;
        $this->deadLine = $deadLine;
        $this->activeStatus = $activeStatus;
    }

    public function getAllStatuses()
    {
        return self::STATUSES;
    }

    public function getAllActions()
    {
        return self::ACTIONS;
    }

    public function getNextStatus($action, $role)
    {
        $next = 'unknown';
        foreach (self::CONVERSIONS as $item) {
            if ($item['from'] == $this->activeStatus
                && $item['name'] == $action
                && $item['role'] == $role) {
                $next = $item['to'];
            }
        }
        return $next;
    }

}
