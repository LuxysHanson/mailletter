<?php

namespace app\commands;

use app\services\GenerateService;
use app\services\MailLetterService;
use yii\console\Application;
use yii\console\Controller;

/**
 * Class MailLetterController
 * @package app\commands
 */
class MailLetterController extends Controller
{

    /**
     * @var GenerateService
     */
    protected $generateService;

    /**
     * @var MailLetterService
     */
    private $service;

    public function __construct(
        string $id,
        Application $module,
        MailLetterService $service,
        GenerateService $generateService,
        array $config = []
    )
    {
        $this->service = $service;
        $this->generateService = $generateService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Запуск крона, для тестовой задачи №2
     *
     * Можно указать $city_id для конкретного города,
     * если не указана идет общая обработка
     *
     * @param null|int $city_id
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    public function actionIndex($city_id = null)
    {
        $this->service->sendMailLetter($city_id);
    }

    /**
     * Генерация списка подписчиков
     *
     * $count - квадрат этого числа (количество подписчиков),
     * которое хотите cгенерировать
     *
     * @param int $count
     * @throws \yii\db\Exception
     */
    public function actionGenerate($count = 100)
    {
        $message = $this->generateService->subscriberListGeneration($count);
        echo $message. PHP_EOL;
    }

}
