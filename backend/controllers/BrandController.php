<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{

    //品牌brand展示列表
    public function actionIndex()
    {
        //分页 总条数 每页显示条数 当前第几页

//        $query=Brand::find()->where(["status"=>[1,0]]);  ->orderBy('sort desc')
        $query=Brand::find()->where(['!=','status','-1']) ->orderBy('sort');
        //总条数
        $total = $query->count();
        //每页显示条数 2
        $perPage = 2;
        // 初始化分页
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        //查询所有的品牌数据
        $brands=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',["brands"=>$brands,"pager"=>$pager]);
    }

    //添加品牌
    public function actionAdd(){
        //实例化品牌模型
        $model=new Brand();
        //实例化请求
        $request=new Request();
        //如果是post提交
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //得到上传文件对象
            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
            //验证数据
            if($model->validate()){
                //处理图片
                //有文件上传
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //创建文件夹
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //图片保存相对路径
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //保存图片
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //给logo复制
                    $model->logo = $fileName;
                }
                //保存数据不验证
                $model->save(false);
                //提示
                \Yii::$app->session->setFlash("success","品牌添加成功");
                //跳转到添加
                return $this->redirect(["brand/index"]);
            }else{
                var_dump($model->getErrors());//错误信息
                exit;
            }
        }
        //默认选
        $model->status=1;
        //加载添加页面
        return $this->render("add",["model"=>$model]);
    }

    //修改
    public function actionEdit($id){
        //得到品牌对象
        $model=Brand::findOne(["id"=>$id]);
        //实例化请求
        $request=new Request();
        //如果是post提交
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,"imgFile");
            //验证数据
            if($model->validate()){
                //处理图片
                //有文件上传
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //创建文件夹
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    //图片保存相对路径
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //保存图片
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //给logo复制
                    $model->logo = $fileName;
                }
                //保存数据不验证
                $model->save(false);
                //提示
                \Yii::$app->session->setFlash("info","品牌修改成功");
                //跳转到添加
                return $this->redirect(["brand/index"]);
            }else{
                var_dump($model->getErrors());//错误信息
                exit;
            }
        }
        //加载添加页面
        return $this->render("add",["model"=>$model]);
    }

    //删除
    public function actionDel($id){
        //得到品牌对象
        $model=Brand::findOne(["id"=>$id]);
        $model->status=-1;
        $model->save(false);
        //提示
        \Yii::$app->session->setFlash("danger","品牌删除成功");
        return $this->redirect(["brand/index"]);
    }

}
