<?php

namespace frontend\controllers;

use TaskForce\GeoCoder;

class AddressController extends SecureController
{
    public function actionLocation(string $search)
    {
        $geoCoder = new GeoCoder();

        return $this->asJson($geoCoder->findAddressesByRequest($search));
    }
}