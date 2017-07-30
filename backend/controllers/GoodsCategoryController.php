<?php

namespace backend\controllers;

use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\models\GoodsCategory;
use backend\filters\RbacFilter;

class GoodsCategoryController extends \yii\web\Controller
{

    //商品展示
    public function actionIndex()
    {
        //查询出所有分类
        $models = GoodsCategory::find()->orderBy('tree,lft')->asArray()->all();

        return $this->render('index',['models'=>$models]);
    }

    //添加商品
    public function actionAdd(){
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类
            if($model->parent_id) {
                //添加非一级分类
                $parent = GoodsCategory::findOne(['id' => $model->parent_id]);//获取上一级分类
                if ($parent) {
                    $model->prependTo($parent);//添加到上一级分类下面
                } else {
                    throw new HttpException(404, '上级分类不存在');
                }

                }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }

        return $this->render('add',['model'=>$model]);

    }

    //修改商品
    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            try{
                //判断是否是添加一级分类
                if($model->parent_id){
                    //非一级分类
                    $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    if($category){
                        $model->appendTo($category);
                    }else{
                        throw new HttpException(404,'上级分类不存在');
                    }
                }else{
                    //一级分类
                    //bug fix:修复根节点修改为根节点的bug
                    if($model->oldAttributes['parent_id']==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','分类添加成功');
                return $this->redirect(['index']);
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel($id){
        //根据id查出对应的数据将其修改为删除状态
        $model=GoodsCategory::findOne(["id"=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品分类不存在');
        }
        if(!$model->isLeaf()){//判断是否是叶子节点，非叶子节点说明有子分类
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }
        $model->deleteWithChildren();
        //提示
        \Yii::$app->session->setFlash("danger","商品分类删除成功");
        return $this->redirect(["goods-category/index"]);
    }


    //测试插件用法
    public function actionText()
    {
            //创建一个根节点
//        $category = new GoodsCategory();
//        $category->name='家用电器';
//        $category->makeRoot();

        //创建子节点
//        $category2 = new GoodsCategory();
//        $category2->name= '大家电';
//        $category = GoodsCategory::findOne(['id'=>15]);
//        $category2->parent_id=$category->id;
//        $category2->prependTo($category);

        //删除节点
       // $cate=GoodsCategory::findOne(['id'=>21])->delete();
        //echo 'OK';
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

}