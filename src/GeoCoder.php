<?php

namespace TaskForce;

use Exception;
use frontend\models\City;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class GeoCoder
{
    public const HTTP_GEOCODE_MAPS_YANDEX_RU = 'http://geocode-maps.yandex.ru/';
    public const MAX_LOCATIONS = 5;
    public const FORMAT_JSON = 'json';
    private const DAY_IN_SECONDS = 3600 * 24;
    private string $apiKey = '';
    private Client $apiClient;
    private string $userCity;

    /**
     * GeoCoder constructor.
     * @throws NotFoundHttpException
     */
    public function __construct()
    {
        $this->apiKey = Yii::$app->params['yandex_api_key'];
        $this->apiClient = new Client(['base_uri' => self::HTTP_GEOCODE_MAPS_YANDEX_RU]);
        $userCityModel = City::findOrFail(Yii::$app->user->identity->city_id);
        $this->userCity = $userCityModel['city'];
    }

    /**
     * @param string $userRequest
     * @return array|null
     * @throws GuzzleException
     */
    public function findAddressesByRequest(string $userRequest): array|null
    {
        $userRequest = $this->prepareRequest($userRequest);
        $key = md5($userRequest);
        $data = Yii::$app->cache->get($key);

        if ($data) {
           $data = json_decode($data);
        } else {
           $data = $this->getAddressesByApi($userRequest);
           $this->saveToCache(key: $userRequest, data: $data);
        }

        return $data;
    }

    /**
     * @param array $GeoObjects
     * @return array|null
     * @throws Exception
     */
    private function convertLocations(array $GeoObjects): ?array
    {
        $locations = [];
        foreach ($GeoObjects as $item) {
            $pointData = ArrayHelper::getValue($item, 'GeoObject.Point.pos');
            $coords = explode(" ", $pointData);
            $lng = $coords[0];
            $lat = $coords[1];
            $city = ArrayHelper::getValue(
                $item,
                'GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.'
                . 'AdministrativeArea.SubAdministrativeArea.Locality.LocalityName'
            );

            $text = ArrayHelper::getValue($item, 'GeoObject.metaDataProperty.GeocoderMetaData.text');

            if (stripos($text, $this->userCity)) {
                array_push($locations, ['text' => $text, 'lat' => $lat, 'lng' => $lng, 'city' => $city]);
            }
        }

        return $locations;
    }

    /**
     * @param Request $apiRequest
     * @param $response_data
     * @throws Exception
     */
    public function findJsonErrors(Request $apiRequest, $response_data): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ServerException("Invalid json format", $apiRequest);
        }
        if ($error = ArrayHelper::getValue($response_data, 'error.info')) {
            throw new BadResponseException("API error: " . $error, $apiRequest);
        }
    }

    /**
     * @param string $userRequest
     * @return mixed
     * @throws GuzzleException
     */
    public function getResponseData(string $userRequest): mixed
    {
        $apiRequest = new Request('GET', 'check');
        $response = $this->apiClient->request(
            'GET',
            '1.x',
            [
                'query' => [
                    'geocode' => $userRequest,
                    'apikey' => $this->apiKey,
                    'format' => self::FORMAT_JSON,
                    'results' => self::MAX_LOCATIONS
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new BadResponseException("Response error: " . $response->getReasonPhrase(), $apiRequest);
        }
        $content = $response->getBody()->getContents();

        return Json::decode($content);
    }

    /**
     * @throws GuzzleException
     */
    public function getCoordinates($location): ?array
    {
        return $this->findAddressesByRequest($location)[0] ?? null;
    }

    private function prepareRequest(string $request): string
    {
        $request = trim($request);

        //by default use the city from the user profile
        if (!$request) {
            $request = $this->userCity;
        }
        //add city to request if not exists
        if  (!strpos(mb_strtolower($request), mb_strtolower($this->userCity))) {
            $request = $this->userCity . ', ' . $request;
        }

        return $request;
    }

    /**
     * @param string $userRequest
     * @return array|null
     * @throws GuzzleException
     */
    private function getAddressesByApi(string $userRequest): ?array
    {
        try {
            $responseData = $this->getResponseData($userRequest);
            $geoObjects = ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember');
            $locations = $this->convertLocations($geoObjects);
            $result = null;
            if (is_array($locations)) {
                $result = $locations;
            }
        } catch (Exception) {
            $result = null;
        }

        return $result;
    }

    private function saveToCache(string $key, ?array $data): void
    {
        $key = md5($key);
        $value = json_encode($data);
        Yii::$app->cache->set($key, $value, self::DAY_IN_SECONDS, new TagDependency(['tags' => 'geo-coder-locations']));
    }
}