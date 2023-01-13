<?php

namespace app\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{

    public static function tableName()
    {
        return '{{tbl_post_original}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'brand_id',
                    'price_for_tour',
                    'priority',
                    'city_id',
                    'hidden_from_site'
                ], 'integer'
            ],
            [['title', 'info', 'cur'], 'string'],
            [['endDate'], 'safe']
        ];
    }

}