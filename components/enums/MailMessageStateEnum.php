<?php

namespace app\components\enums;

use app\components\Enum;
use Yii;

class MailMessageStateEnum extends Enum
{

    const STATE_NEW = 0;
    const STATE_IN_PROCESS = 1;
    const STATE_FINISH = 2;

    public static function labels()
    {
        return [
            self::STATE_NEW => Yii::t('app', 'Создана'),
            self::STATE_IN_PROCESS => Yii::t('app', 'Запущена, рассылается'),
            self::STATE_FINISH => Yii::t('app', 'Отправка окончена')
        ];
    }

}
