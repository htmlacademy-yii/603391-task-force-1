<?php

namespace frontend\models\forms;

use frontend\models\Notification;
use frontend\models\UserNotification;
use Yii;
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

    /**
     * @param bool $defaultValue
     */
    public function init(bool $defaultValue = false)
    {
        $this->notificationsId = ArrayHelper::map(Notification::find()->select(['id', 'name_rus'])->all(), 'id', 'name_rus');

        foreach ($this->notificationsId as $key => $element) {
            $this->notifications [$key] = $defaultValue;
        }
    }

    /**
     * @param string $name
     * @return bool|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->notifications)) {
            return $this->notifications [$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getNotificationsState(): array
    {
        return $this->notifications;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->notifications )) {
            $this->notifications [$name] = $value;
        }
    }

    /**
     * @return array|null
     */
    public function attributeLabels()
    {
        return $this->notificationsId;
    }

    /**
     * @param array $values
     */
    public function updateProperties(array $values): void
    {
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->notifications )) {
                $this->notifications[$name] = $value;
            }
        }
    }

    /**
     * @param int $id
     */
    public function setOneNotification(int $id): void
    {
        foreach ($this->notificationsId as $key => $element) {
            $this->notifications [$key] = false;
        }
        $this->notifications [$id] = true;
    }

    /**
     *
     */
    public function saveData()
    {
        $userId = (int)yii::$app->user->id;
        UserNotification::deleteAll(['user_id' => (int)$userId]);
        foreach ($this->notifications as $name => $value) {
            if ((bool)$value) {
                $notification = new UserNotification();
                $notification->user_id = $userId;
                $notification->notification_id = $name;
                $notification->save();
            }
        }
    }

    /**
     *
     */
    public function loadNotify(): void
    {
        $userId = (int)Yii::$app->user->identity->id;
        $this->notificationsId = ArrayHelper::map(Notification::find()->select(['id', 'name'])->all(), 'id', 'name');
        $list = UserNotification::find()->select(['notification_id'])
            ->where(['user_id'=>$userId])->asArray()
            ->all();
        $this->init();
        foreach ($list as $element) {
            $this->notifications[$element['notification_id']] = '1';
        }
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        if ($formName) {
            $this->notifications = $data[$formName]['notifications'];
            return true;
        }

        return false;
    }

}
