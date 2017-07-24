<?php

namespace backend\models;

use Yii;

class GoodsIntro extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'goods_intro';
    }

    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'content' => '商品描述',
        ];
    }
}
