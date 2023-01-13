<?php

namespace app\services;

use app\repositories\CityRepository;
use app\repositories\MailRepository;
use Yii;
use yii\db\Exception;

/**
 * Class GenerateService
 * @package app\services
 */
class GenerateService
{

    /**
     * @var MailRepository
     */
    private $mailRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    public function __construct(
        CityRepository $cityRepository,
        MailRepository $mailRepository
    )
    {
        $this->cityRepository = $cityRepository;
        $this->mailRepository = $mailRepository;
    }

    /**
     * @param int $count
     * @return string
     * @throws Exception
     */
    public function subscriberListGeneration(int $count): string
    {
        $tbl_city_ids = $this->cityRepository->getCitiesForMailingLetters();
        return $this->generateData($count, $tbl_city_ids);
    }

    /**
     * @param int $count
     * @param array $city_ids
     * @return string
     * @throws Exception
     */
    private function generateData(int $count, array $city_ids): string
    {

        $transaction = Yii::$app->db->beginTransaction();

        for ($i = 0; $i < $count; $i++) {
            $mail = [];
            for ($j = 0; $j < $count; $j++) {
                $generate_data = $this->mailRepository->getGenerationTemplate($city_ids);
                $mail[] = array_values($generate_data);
            }

            try {
                $this->mailRepository->batchInsert($mail);
                unset($mail);
            } catch (Exception $exception) {
                $transaction->rollBack();
                Yii::error($exception->getMessage());
                return 'Failed to generate data!';
            }

        }

        $transaction->commit();
        return 'Data generation is complete!';
    }

}
