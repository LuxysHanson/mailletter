<?php

namespace app\services;

use app\models\entities\MailEntity;
use YarCode\Yii2\QueueMailer\Mailer;
use Yii;
use yii\base\Component;

/**
 *
 * Сервис для отправки писем
 *
 * Class MailService
 * @package app\services
 */
class MailService extends Component
{

    /**
     * @var Mailer
     */
    protected $notifier;

    public function init()
    {
        $this->notifier =  Yii::$app->getMailer();
        parent::init();
    }

    /**
     * @param MailEntity $entity
     * @return bool
     */
    public function send(MailEntity $entity): bool
    {
        return $this->notifier
            ->compose()
            ->setHtmlBody($entity->getContent())
            ->setFrom($entity->getSenderEmail())
            ->setTo($entity->getEmail())
            ->setSubject($entity->getSubject())
            ->send();
    }

}
