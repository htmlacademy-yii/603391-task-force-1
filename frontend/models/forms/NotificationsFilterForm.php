<?php

namespace frontend\models\forms;

use frontend\models\Notification;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-write int $oneNotification
 * @property-read array $notificationsState
 */
class NotificationsFilterForm extends Model
{
    private ?array $notifications = [];
    private ?array $notificationsId = null;

    public function init(bool $defaultValue = true): void
    {
        $this->notificationsId = ArrayHelper::map(Notification::find()->select(['id', 'name_rus'])->all(), 'id', 'name_rus');

        foreach ($this->notificationsId as $key => $element) {
            $this->notifications [$key] = $defaultValue;
        }
    }

    public function __get($name): ?bool
    {
        if (array_key_exists($name, $this->notifications)) {
            return $this->notifications [$name];
        }

        return null;
    }

    public function getNotificationsState(): array
    {
        return $this->notifications;
    }

    public function __set($name, $value): void
    {
        if (array_key_exists($name, $this->notifications )) {
            $this->notifications [$name] = $value;
        }
    }

    public function attributeLabels(): ?array
    {
        return $this->notificationsId;
    }

    public function updateProperties(array $values): void
    {
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->notifications )) {
                $this->notifications[$name] = $value;
            }
        }
    }

    public function setOneNotification(int $id): void
    {
        foreach ($this->notificationsId as $key => $element) {
            $this->notifications [$key] = false;
        }
        $this->notifications [$id] = true;
    }


}
