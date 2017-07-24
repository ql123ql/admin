<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveRecord;


class GoodsCategory extends ActiveRecord
{

    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }

    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    public static function tableName()
    {
        return 'goods_category';
    }

//规则
    public function rules()
    {
        return [
            [['name','parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            //分类名不能重复
            ['name','unique'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上级分类id',
            'intro' => '简介',
        ];
    }
    //嵌套集合插件行为
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',//必须打开（多个一级分类）
                 'leftAttribute' => 'lft',
                 'rightAttribute' => 'rgt',
                 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    public static function getZtreeNodes()
    {
        $nodes =  self::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];
        return $nodes;
    }
    //异常提示信息
    public static function exceptionInfo($msg)
    {
        $infos = [
            'Can not move a node when the target node is same.'=>'不能修改到自己节点下面',
            'Can not move a node when the target node is child.'=>'不能修改到自己的子孙节点下面',
        ];
        return isset($infos[$msg])?$infos[$msg]:$msg;
    }




}