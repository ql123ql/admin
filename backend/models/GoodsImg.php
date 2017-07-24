<?php

namespace backend\models;

use Yii;

class GoodsImg extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'goods_photo';
    }

    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'path' => '图片',
        ];
    }
}
