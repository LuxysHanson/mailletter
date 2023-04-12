<?php
namespace models\entities;

use app\models\entities\TourEntity;

class TourEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var TourEntity
     */
    private $entity;

    /*public function testTourCorrectData()
    {

        $this->entity = new TourEntity();
        $this->entity->setOfferId(123);
        $this->entity->setCurrency('kzt');
        $this->entity->setExpand([
            'success' => []
        ]);

        verify($this->entity->getOfferId())->isInt();
        verify($this->entity->getCurrency())->isString();
        verify($this->entity->getExpand())->arrayNotCount(0);
    }

    public function testTourWrongData()
    {

        $this->entity = new TourEntity();

        verify($this->entity->getOfferId())->isNotInt();
        verify($this->entity->getCurrency())->isNotString();
        verify($this->entity->getExpand())->empty();
    }*/

}
