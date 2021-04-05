<?php

namespace TaskForce\Helpers;

class UserData
{
    const SERVICE_URL = 'http://ip-api.com/json/';
    const LANG_RU = '?lang=ru';
    const VALID_IP = "/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/";

    static function getCityByIp(string $ip): ?string
    {
        if (self::validateIp($ip)) {
            return false;
        }
        $curlHandle = curl_init(self::SERVICE_URL . $ip . self::LANG_RU);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        $resource = curl_exec($curlHandle);
        curl_close($curlHandle);
        $resource = json_decode($resource, true);

        return $resource['city'] ?? false;
    }

    static function validateIp (?string $ip): bool {
        return preg_match(self::VALID_IP, $ip) === true;
    }
}