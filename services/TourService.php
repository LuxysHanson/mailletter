<?php

namespace app\services;

use app\components\Hydrator;
use app\models\entities\CurlEntity;
use app\models\entities\TourEntity;
use Yii;
use yii\base\Model;

class TourService
{

    /**
     * @var Hydrator|object
     */
    protected $hydrator;

    /**
     * @var TourEntity
     */
    private $entity;

    public function __construct(TourEntity $tourEntity)
    {
        $this->entity = $tourEntity;
        $this->hydrator = Yii::createObject(Hydrator::class);
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \app\components\exceptions\CurlException
     */
    public function send(): array
    {

        $curlEntity = $this->createCurlEntity();

        $curlService = new CurlService($curlEntity);
        return $curlService->sendRequest();
    }

    /**
     * @return object|Model|CurlEntity
     * @throws \ReflectionException
     */
    private function createCurlEntity(): CurlEntity
    {

        $entity_data = [
            'url' => $this->getApiUrl(),
            'type' => 'GET',
            'headers' => [
                'Content-Type: application/json'
            ]
        ];

        return $this->hydrator->hydrate(CurlEntity::class, $entity_data);
    }

    private function getApiUrl(): string
    {
        $api_url = 'https://api.bronix.com/common/v1/hot-offers/view';
        $api_get_params = [
            'id' => $this->entity->getOfferId(),
            'expand' => $this->entity->getExpand(),
            'currency' => $this->entity->getCurrency(),
            'access-token' => Yii::$app->params['tourAccessToken']
        ];
        return $api_url . http_build_query($api_get_params);
    }

}
