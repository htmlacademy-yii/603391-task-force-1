<?php

namespace TaskForce\Redis;

use yii\redis\Cache;

class RedisCacheWithFallBack extends Cache
{
    const FLAG = 4;
    public string $fallBack;
    public string $mainCache;
    private $currentCache;

    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        return $this->currentCache->getValue($key);
    }

    public function init()
    {
        parent::init();
        $socket = stream_socket_client(
            'tcp://' . \Yii::$app->redis->hostname . ":" . \Yii::$app->redis->port,
            $errorNumber,
            $errorDescription,
            ini_get('default_socket_timeout'),
            self::FLAG
        );
        if ($socket) {
            $this->currentCache = \Yii::$app->{$this->mainCache};
        } else {
            $this->currentCache = \Yii::$app->{$this->fallBack};
        }
        fclose($socket);
    }

}