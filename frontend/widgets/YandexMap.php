<?php

namespace frontend\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\web\View;

class YandexMap extends Widget
{
    public int $lat;
    public int $lng;

    /**
     * Registers the needed assets.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $view = $this->getView();
        $apiKey = Yii::$app->params['yandex_api_key'];
        $yandexApiJs = "https://api-maps.yandex.ru/2.1/?apikey=$apiKey&lang=ru_RU";
        $view->registerJSFile($yandexApiJs, $options = [$position = View::POS_HEAD], $key = null);

        $mapJS = <<<'MAPJS'
        var taskMap;
        ymaps.ready(function(){
                // Указывается идентификатор HTML-элемента.
                var taskMap = new ymaps.Map("map", {
                    center: [%s,%s],
                    zoom: 17,
                });
            });
        ymaps.ready(init);
        MAPJS;
        $mapJS = sprintf($mapJS, $this->lat, $this->lng);
        $view->registerJS($mapJS);
    }

    /**
     * Map html block
     * @return string|null
     */
    public function run(): string
    {
        return '<div class="content-view__map" id="map" style="width: 361px; height: 292px"></div>';
    }
}