<?php

namespace app\commands;

use app\models\City;
use app\services\MailLetterService;
use Exception;
use Faker\Factory;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class MailLetterController
 * @package app\commands
 */
class MailLetterController extends Controller
{

    /**
     * @var MailLetterService
     */
    private $service;

    public function __construct($id, $module, MailLetterService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Запуск крона, для тестовой задачи №2
     *
     * Можно указать $city_id для конкретного города,
     * если не указана идет общая обработка
     *
     * @param null|int $city_id
     * @return int
     */
    public function actionIndex($city_id = null)
    {
        try {
            $this->service->sendMailLetter($city_id);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
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

        $tbl_city_ids = City::find()->select('id')->where([
            'allow_email_subscription' => 1
        ])->column();

        $faker = Factory::create();
        $transaction = Yii::$app->db->beginTransaction();

        for ($i = 0; $i < $count; $i++) {
            $mail = [];
            for ($j = 0; $j < $count; $j++) {
                $mail[] = [
                    $faker->unique()->email,
                    rand(0, 1),
                    rand(0, 1),
                    (int) $tbl_city_ids[array_rand($tbl_city_ids, 1)]
                ];
            }

            try {
                Yii::$app->db
                    ->createCommand()
                    ->batchInsert('tbl_mail', ['email', 'active', 'del', 'city'], $mail)
                    ->execute();
                unset($mail);
            } catch (\yii\db\Exception $exception) {
                $transaction->rollBack();
                Yii::error($exception->getMessage());
                return 'Failed to generate data!';
            }

        }

        $transaction->commit();
        return 'Data generation is complete!';
    }

}
