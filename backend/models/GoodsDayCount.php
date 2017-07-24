<?php

namespace backend\models;

use Yii;

class GoodsDayCount extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'goods_day_count';
    }

    public function rules()
    {
        return [
            [['day'], 'safe'],
            [['count'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'day' => '日期',
            'count' => '商品数',
        ];
    }
}
