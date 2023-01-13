<?php

namespace app\repositories;

use app\models\Mail;
use Faker\Factory;
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

    public function getGenerationTemplate(array $city_ids): array
    {

        $faker = Factory::create();

        return [
            $faker->unique()->email,
            rand(0, 1),
            rand(0, 1),
            (int) $city_ids[array_rand($city_ids, 1)]
        ];
    }

    /**
     * @param array $data
     * @throws \yii\db\Exception
     */
    public function batchInsert(array $data): void
    {
        $this->queryBuilder
            ->createCommand()
            ->batchInsert($this->getTableName(), ['email', 'active', 'del', 'city'], $data)
            ->execute();
    }

}
