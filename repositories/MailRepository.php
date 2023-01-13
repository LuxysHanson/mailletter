<?php

namespace app\repositories;

use app\models\Mail;
use yii\db\ActiveRecord;
use yii\db\DataReader;

class MailRepository extends BaseRepository
{

    public function getModel(): ActiveRecord
    {
        return new Mail;
    }

    /**
     * @param int $city_id
     * @return DataReader
     * @throws \yii\db\Exception
     */
    public function getSubscriberListByCityId(int $city_id): DataReader
    {

        $command = $this->find()
            ->andWhere([
                'city' => $city_id,
                'active' => 1,
                'del' => 0
            ])->createCommand();

        return $command->query();
    }

}
