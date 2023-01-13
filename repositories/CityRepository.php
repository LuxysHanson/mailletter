<?php

namespace app\repositories;

use app\models\City;
use yii\db\ActiveRecord;

/**
 * Class CityRepository
 * @package app\repositories
 */
class CityRepository extends BaseRepository
{

    public function getModel(): ActiveRecord
    {
        return new City;
    }

    /**
     * @return array
     */
    public function getCitiesForMailingLetters(): array
    {
        return $this->find()
            ->select('id')
            ->where([
                'allow_email_subscription' => 1
            ])->column();
    }

}
