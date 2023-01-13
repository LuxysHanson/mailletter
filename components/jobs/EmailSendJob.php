<?php

namespace app\components\jobs;

use app\components\events\EmailSendEvent;
use app\models\entities\MailEntity;
use app\models\MailMessage;
use app\repositories\MailMessageRepository;
use app\services\AmplitudeService;
use app\services\MailService;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class EmailSendJob extends BaseObject implements JobInterface
{

    /**
     * @var MailEntity
     */
    public $entity;

    public $subscribeId;
    public $messageId;

    public function execute($queue)
    {

        $mailService = new MailService();
        $send = $mailService->send($this->entity);

        // изменяем данные в рассылке
        $this->mailingDataChanges($send);

        if ($send) {
            // после успешной отправки, отправляем событие email send
            $this->sendingAnEventToAmplitude();
        }
    }

    protected function mailingDataChanges(bool $send): void
    {

        $mailMessageRepository = new MailMessageRepository();

        /** @var MailMessage $mail_message */
        $mail_message = $mailMessageRepository->oneById($this->messageId);

        $mailMessageRepository->mailingDataChanges($mail_message, $send);
        $mailMessageRepository->save($mail_message);
    }

    /**
     * @throws \ReflectionException
     * @throws \app\components\exceptions\CurlException
     */
    private function sendingAnEventToAmplitude()
    {

        $emailSend = new EmailSendEvent();
        $emailSend->setEmail($this->entity->getEmail());
        $emailSend->setSubscribeId($this->subscribeId);

        $amplitudeService = new AmplitudeService($emailSend);
        $amplitudeService->sendEvent();
    }

}
