<?php

namespace TaskForce;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;

class GeoCoder
{
    public const MAX_LOCATIONS = 5;
    public const FORMAT_JSON = 'json';
    private string $apiKey = '';
    private Client $apiClient;

    /**
     * GeoCoder constructor.
     */
    public function __construct()
    {
        $this->apiKey = Yii::$app->params['yandex_api_key'];
        $this->apiClient = new Client(['base_uri' => 'http://geocode-maps.yandex.ru/']);
    }

    /**
     * @param string $userRequest
     * @return array|null
     * @throws GuzzleException
     */
    public function findAddressesByRequest(string $userRequest): ?array
    {
        if (!$userRequest) {
            return null;
        }

        try {
            $responseData = $this->getResponseData($userRequest);
            $geoObjects = ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember');
            $locations = $this->convertLocations($geoObjects);


            $result = null;
            if (is_array($locations)) {
                $result = $locations;
            }
        } catch (RequestException $e) {
            $result = null;
        }

        return $result;
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
            $lat = $coords[1];
            $lng = $coords[0];
            $city= ArrayHelper::getValue($item,
                                  'GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName');
            $text = ArrayHelper::getValue($item, 'GeoObject.metaDataProperty.GeocoderMetaData.text');
            array_push($locations, ['text'=>$text,'lat'=> $lat, 'lng'=>$lng, 'city'=>$city]);
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
     * @throws Exception
     */
    public function getResponseData(string $userRequest)
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

        $response_data = json_decode($content, true);
        $this->findJsonErrors($apiRequest, $response_data);

        return $response_data;
    }

    public function getCoordinates($location): ?array
    {

        return  $this->findAddressesByRequest($location)[0];
    }


}