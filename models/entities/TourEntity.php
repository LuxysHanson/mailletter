<?php

namespace app\models\entities;

use yii\base\Model;

class TourEntity extends Model
{

    private $offerId;
    private $currency;
    private $expand = [];


    public function getOfferId()
    {
        return $this->offerId;
    }

    public function setOfferId(int $offerId): void
    {
        $this->offerId = $offerId;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getExpand(): array
    {
        return $this->expand;
    }

    public function setExpand(array $expand): void
    {
        $this->expand = $expand;
    }

}
