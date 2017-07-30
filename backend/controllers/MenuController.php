<?php

namespace backend\controllers;

use backend\models\Menu;
use backend\filters\RbacFilter;

class MenuController extends \yii\web\Controller
{
    //菜单列表
    public function actionIndex()
    {
        $models = Menu::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['models'=>$models]);
    }
    //添加菜单
    public function actionAdd(){
        $model = new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            //跳转列表页
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改菜单
    public function actionEdit($id){
        $model = Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            //跳转列表页
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除菜单
    public function actionDel($id){
        $model = Menu::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['menu/index']);
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
