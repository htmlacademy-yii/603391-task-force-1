<?php

namespace TaskForce\Redis;

use Exception;
use Yii;
use yii\caching\FileCache;

class RedisCacheWithFallBack
{
    const REDIS_ADDRESS = 'tcp://redis:6379';

    /**
     * @return object
     */
    public static function getConnection(): object
    {
        $isConnected = false;
        try {
            $socket = stream_socket_client(
                self::REDIS_ADDRESS,
                $errorNumber,
                $errorDescription,
                ini_get('default_socket_timeout')
            );
            $isConnected = (bool)$socket;
            fclose($socket);
        } catch (Exception) {
        }

        return ($isConnected) ? Yii::$app->redisCache : new FileCache();
    }
}