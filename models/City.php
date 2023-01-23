<?php

namespace app\models;

use yii\db\ActiveRecord;

class City extends ActiveRecord
{

    public static function tableName()
    {
        return '{{tbl_city}}';
    }

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['allow_email_subscription'], 'integer']
        ];
    }

}
