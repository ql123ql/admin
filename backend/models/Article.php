<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    //定义状态
    private  $status_options=[
        -1=>"删除" , 0=>"隐藏", 1=>"正常"
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }


    //这个静态方法用来得到文章的三种的状态
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
    //得到文章分类id
    public function getArticleCategoryOptions(){
        return ArrayHelper::map(ArticleCategory::find()->all(),"id","name");
    }

    //定义一个只读的属性得到状态
    public function getStatusText(){
        if(array_key_exists($this->status,$this->status_options)){
            return $this->status_options[$this->status];
        }
        return '未知';
    }
    /**
     * @inheritdoc
     */
    //规则
    public function rules()
    {
        return [
            [["name","intro",'sort','status'],"required","message"=>"{attribute}不能为空"],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    //对应关系
//    public function getArticle(){
//        return $this->hasOne(Article::className(),['id'=>'article_id']);
//    }

    //属性名称
    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'name' => '文章名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
