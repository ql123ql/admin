<?php

namespace backend\models;

use Yii;

class Goods extends \yii\db\ActiveRecord
{
    //状态选项
    static public $is_on_saleOptions=[0=>'下架',1=>'在售'];
    static public $statusOptions=[1=>'正常',0=>'回收站'];

    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public function getCate_gory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function getPhotos(){
        return $this->hasMany(GoodsImg::className(),['goods_id'=>'id']);
    }
    public static function tableName()
    {
        return 'goods';
    }

    //规则
    public function rules()
    {
        return [
            [['name','brand_id','market_price','shop_price','stock','is_on_sale','status'],'required'],
            [['brand_id', 'stock', 'sort','goods_category_id'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name','sn','logo'], 'string', 'max' => 150],
        ];
    }

    //属性名称
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sn' => '货号',
            'logo' => '商品logo',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'name' => '商品名称',
            'goods_category_id' => '商品分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
