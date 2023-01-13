<?php

namespace app\models;

use yii\db\ActiveRecord;

class Mail extends ActiveRecord
{

    public static function tableName()
    {
        return '{{tbl_mail}}';
    }

    public function rules()
    {
        return [
            [['email'], 'email'],
            [['city', 'active', 'del'], 'integer'],
        ];
    }

}