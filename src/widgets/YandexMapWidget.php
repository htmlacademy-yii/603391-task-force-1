<?php

namespace TaskForce\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class YandexMapWidget extends Widget
{
    public string $lat;
    public string $lng;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->lat !== '' && $this->lng !== '') {
            return true;
        }

        return false;
    }

    /**
     * Registers the needed JS.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!$this->validate()) { return false;}
        $view = $this->getView();
        $apiKey = Yii::$app->params['yandex_api_key'];
        $yandexApiJs = "https://api-maps.yandex.ru/2.1/?apikey=$apiKey&lang=ru_RU";
        $view->registerJSFile($yandexApiJs);

        $mapJS = <<<'MAPJS'
        var taskMap;
        ymaps.ready(function(){
                var taskMap = new ymaps.Map("map", {
                    center: [%s,%s],
                    zoom: 17,
                });
            });
        MAPJS;
        $mapJS = sprintf($mapJS, $this->lat, $this->lng);
        $view->registerJS($mapJS);

        return true;
    }

    /**
     * Map html block
     * @return string|null
     */
    public function run()
    {
        if (!$this->validate()) { return null;}

        return '<div class="content-view__map" id="map" style="width: 361px; height: 292px"></div>';
    }
}