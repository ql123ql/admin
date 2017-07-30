<?php

namespace backend\models;

use Yii;


class ArticleDetail extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'article_detail';
    }

    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'article_id' => 'ID',
            'content' => '内容',
        ];
    }
}
