<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use backend\filters\RbacFilter;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{

    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页,
        //全部状态不为-1的文章分类  ->orderBy('sort desc')
        $query=ArticleCategory::find()->where(['!=','status','-1'])->orderBy('sort');
        //总条数
        $total = $query->count();
        //每页显示条数 2
        $perPage = 2;
        // 初始化分页
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);

        //查询所有文章分类的数据
        $articles=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',["articles"=>$articles,"pager"=>$pager]);
    }

    //添加文章分类
    public function actionAdd(){
        //实例化文章分类
        $model=new ArticleCategory();
        // 实例化请求
        $request=new Request();
        //如果是post提交
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //保存数据
                $model->save();
                //提示
                \Yii::$app->session->setFlash("success","添加文章分类成功");
                //跳转
                return $this->redirect(["article-category/index"]);
            }
        }
        //初始化状态
        $model->status=1;
        //加载文章分类添加页面
        return $this->render("add",["model"=>$model]);

    }

    //修改文章分类
    public function actionEdit($id){
        //得到文章分类
        $model=ArticleCategory::findOne(["id"=>$id]);
        // //实例化请求
        $request=new Request();
        //如果是post提交
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //保存数据
                $model->save();
                //提示
                \Yii::$app->session->setFlash("info","修改文章分类成功");
                //跳转
                return $this->redirect(["article-category/index"]);
            }
        }
          //初始化状态
             // $model->status=1;
        //加载文章分类添加页面
        return $this->render("add",["model"=>$model]);

    }

    public function actionDel($id){
        //得到文章对象
        $model=ArticleCategory::findOne(["id"=>$id]);
        $model->status=-1;
        $model->save();
        //提示
        \Yii::$app->session->setFlash("danger","删除文章分类成功");
        return $this->redirect(["article-category/index"]);
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
