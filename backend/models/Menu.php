<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;


class Menu extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'menu';
    }

    public function rules()
    {
        return [
            [['label'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址/路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
    //获取所有子菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    //获取顶级分类
    public static function getPermissions(){
        return ArrayHelper::merge([0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label'));
    }

}






















