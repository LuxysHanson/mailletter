<?php

namespace app\models\entities;

use Yii;
use yii\base\Model;
use yii\web\View;

class MailEntity extends Model
{

    private $email;
    private $senderEmail;
    private $subject;
    private $template;
    private $data = [];

    public function init()
    {
        $this->senderEmail = Yii::$app->params['senderEmail'];
        parent::init();
    }

    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getContent(): string
    {
        return (new View())->renderFile($this->template, $this->data);
    }

}
