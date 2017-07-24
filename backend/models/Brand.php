<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property string $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //保存文件上传对象
    public $imgFile;
    //定义状态
    private  $status_options=[
        -1=>"删除" , 0=>"隐藏", 1=>"正常"
    ];
    //品牌的状态
    public static function getStatusOptions($hide_del=true){
        $options=[
            '-1'=>"删除" ,
            '0'=>"隐藏",
            '1'=>"正常"
        ];
        if($hide_del){
            unset( $options['-1']);
        }
        return $options;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["name","intro",'sort','status','logo'],"required","message"=>"{attribute}不能为空"],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
           /* ['imgFile','file','extensions'=>['jpg','png','gif']],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'imgFile' => 'logo图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    //定义一个只读的属性得到状态
    public function getStatusText(){
        if(array_key_exists($this->status,$this->status_options)){
            return $this->status_options[$this->status];
        }
        return '未知';
    }
}
