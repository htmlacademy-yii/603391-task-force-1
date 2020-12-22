<?php

namespace frontend\controllers;

use GuzzleHttp\Exception\GuzzleException;
use TaskForce\GeoCoder;
use yii\web\Response;

class AddressController extends SecureController
{
    /**
     * @param string $search
     * @return Response
     * @throws GuzzleException
     */
    public function actionLocation(string $search)
    {
        $geoCoder = new GeoCoder();

        return $this->asJson($geoCoder->findAddressesByRequest($search));
    }
}