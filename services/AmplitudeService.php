<?php

namespace app\services;

use app\components\Hydrator;
use app\interfaces\IAmplitudeEvent;
use app\models\entities\CurlEntity;
use Yii;
use yii\base\Model;

/**
 *
 * Сервис для работы с amplitude
 *
 * Class AmplitudeService
 * @package app\services
 */
class AmplitudeService
{

    /**
     * @var Hydrator|object
     */
    protected $hydrator;

    /**
     * @var IAmplitudeEvent
     */
    private $event;

    public function __construct(IAmplitudeEvent $amplitudeEvent)
    {
        $this->event = $amplitudeEvent;
        $this->hydrator = Yii::createObject(Hydrator::class);
    }

    /**
     * @throws \ReflectionException
     * @throws \app\components\exceptions\CurlException
     */
    public function sendEvent()
    {

        $curlEntity = $this->createCurlEntity();

        $curlService = new CurlService($curlEntity);
        $curlService->sendRequest();
    }

    /**
     * @return object|Model|CurlEntity
     * @throws \ReflectionException
     */
    private function createCurlEntity(): CurlEntity
    {

        $post_data = [
                'api_key' => Yii::$app->params['amplitudeToken']
            ] + $this->event->getData();

        $entity_data = [
            'url' => 'https://api2.amplitude.com/batch',
            'type' => 'POST',
            'headers' => [
                'Content-Type: application/json'
            ],
            'postData' => json_encode($post_data)
        ];

        return $this->hydrator->hydrate(CurlEntity::class, $entity_data);
    }

}
