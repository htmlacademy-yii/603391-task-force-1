<?php

namespace TaskForce\widgets;

use frontend\models\City;
use Yii;
use yii\base\Widget;

class CitySelectorWidget extends Widget
{
    public array $cityList;
    public int $currentCityId = 0;

    /**
     * @return string|null
     */
    public function run(): ?string
    {
        parent::run();
        $this->cityList = City::getList();

        if ($isLoggedUser = Yii::$app->user->identity) {
            $session = Yii::$app->session;
            $this->currentCityId =  $session['current_city_id'] ? $session['current_city_id']: $isLoggedUser->city_id;
        }

        return $this->render('@widgets/citySelector/view', ['cities' => $this->cityList, 'currentCityId' => $this->currentCityId]);
    }
}