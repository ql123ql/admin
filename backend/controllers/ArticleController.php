<?php

namespace backend\controllers;

use backend\models\Article;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    //文章展示
    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页,
        //查询数据
        $query=Article::find()->where(["!=","status","-1"]) ->orderBy('sort');
        //得到总数
        $total=$query->count();
        //每页显示数量2
        $perPage = 2;
        $pager=new Pagination([
            "totalCount"=>$total,
            "defaultPageSize"=>$perPage,//此处记住，千万别多打空格
        ]);

        //得到分页的数据
        $articles=$query->limit($pager->limit)->offset($pager->offset)->all();
        //加载index页面，并传送分页数据和分页条
        return $this->render('index',["articles"=>$articles,"pager"=>$pager]);
    }
    //文章添加
    public function actionAdd(){
        //实例化文章
        $model=new Article();
        // //实例化请求
        $request=new Request();
        //如果是post提交
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //设置创建时间
                $model->create_time=time();
                //保存数据
                $model->save();
                //提示
                \Yii::$app->session->setFlash("success","文章添加成功");
                //跳转
                return $this->redirect(["article/index"]);
            }
        }
        //初始化状态
        $model->status=1;
        //加载文章添加页面
        return $this->render("add",["model"=>$model]);
    }

}
