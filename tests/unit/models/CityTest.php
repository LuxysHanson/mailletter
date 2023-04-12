<?php
namespace models;

use app\models\City;

class CityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testCityValidationCorrect()
    {

        $city = new City();
        $city->setAttributes([
           'name' => 'admin',
           'allow_email_subscription' => 0
        ]);

        verify($city->validate())->true();
    }

    public function testCityValidationWrong()
    {

        $city = new City();
        $city->setAttributes([
            'name' => 456,
            'allow_email_subscription' => 'yes'
        ]);

        verify($city->validate())->notTrue();
    }

}
