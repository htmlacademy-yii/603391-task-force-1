<?php

namespace frontend\validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class RemoteEmailValidator extends Validator
{
    const SERVICE_URL = 'https://apilayer.net/api/';
    public $message = 'Указанный email не существует';

    /**
     * @param mixed $value
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function validateValue($value)
    {
        $result = false;

        $client = new Client(
            [
                'base_uri' => self::SERVICE_URL,
            ]
        );

        try {
            $request = new Request('GET', 'check');
            $response = $client->send(
                $request,
                [
                    'query' => ['email' => $value, 'access_key' => Yii::$app->params['apiLayerKey']]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException("Response error: " . $response->getReasonPhrase(), $request, $response);
            }

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException("Invalid json format", $request, $response);
            }

            if ($error = ArrayHelper::getValue($response_data, 'error.info')) {
                throw new BadResponseException("API error: " . $error, $request, $response);
            }

            if (is_array($response_data)) {
                $result = !empty($response_data['mx_found']) && !empty($response_data['smtp_check']);
            }
        } catch (RequestException) {
            $result = true;
        }

        if (!$result) {
            return [$this->message, []];
        }

        return null;
    }
}
