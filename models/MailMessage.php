<?php

namespace app\models;

use app\components\enums\MailMessageStateEnum;
use yii\db\ActiveRecord;

class MailMessage extends ActiveRecord
{

    public static function tableName()
    {
        return '{{tbl_mail_message}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'post_id',
                    'site',
                    'send_count',
                    'error_count',
                    'state',
                    'mailType',
                    'total_count'
                ], 'integer'
            ],
            [['titleBig', 'title', 'content'], 'string'],
            ['state', 'in', 'range' => MailMessageStateEnum::values()],
            [['state', 'mailType'], 'default', 'value' => 0],
            [['addDate', 'startDate', 'endDate'], 'safe']
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->addDate = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }

}