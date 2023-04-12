<?php

namespace api\controllers;

use api\models\City;
use yii\rest\ActiveController;

class CityController extends ActiveController
{

    public $modelClass = City::class;


}
