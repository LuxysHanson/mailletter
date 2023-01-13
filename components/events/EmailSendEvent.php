<?php

namespace app\components\events;

use app\interfaces\IAmplitudeEvent;

class EmailSendEvent implements IAmplitudeEvent
{

    private $email;
    private $subscribeId;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSubscribeId()
    {
        return $this->subscribeId;
    }

    public function setSubscribeId(int $subscribeId): void
    {
        $this->subscribeId = $subscribeId;
    }

    public function getData(): array
    {
        return [
            'events' => [
                'user_id' => $this->email,
                'device_id' => 'mail_'. $this->subscribeId,
                'event_type' => 'Email send',
                'time' => time(),
                'event_properties' => [
                    'Type' => 'mailing',
                    'Mail type' => 'single',
                    'Is auto' => true
                ]
            ]
        ];
    }

}
