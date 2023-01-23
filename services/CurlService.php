<?php

namespace app\services;

use app\components\exceptions\CurlException;
use app\models\entities\CurlEntity;
use Yii;

/**
 *
 * Сервис для работы с curl
 *
 * Class CurlService
 * @package app\services
 */
class CurlService
{

    protected $status_code;

    /**
     * @var CurlEntity
     */
    private $entity;

    public function __construct(CurlEntity $curlEntity)
    {
        $this->entity = $curlEntity;
    }

    /**
     * @return array
     * @throws CurlException
     */
    public function sendRequest(): array
    {

        $curl = curl_init($this->entity->getUrl());

        // установка типа
        $this->setCurlParams($curl);

        $response = curl_exec($curl);
        $this->status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($response, true);

        if ($this->status_code != 200) {
            $exception = new CurlException($result['message'], $this->status_code);
            Yii::$app->errorHandler->logException($exception);
        }

        return $result ?: [];
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param false|resource $curl
     */
    private function setCurlParams($curl): void
    {

        if (in_array($this->entity->getType(), ['POST', 'PUT'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->entity->getPostData());
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->entity->getHeaders());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->entity->getType());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
    }

}
