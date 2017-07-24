<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

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
        //每页显示条数 3
        $perPage = 3;
        // 初始化分页
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage,
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

    //逻辑删除
    public function actionDel($id){
        //根据id查出对应的数据将其修改为删除状态
        $model=Brand::findOne(["id"=>$id]);
        $model->status=-1;
        $model->save(false);
        //提示
        \Yii::$app->session->setFlash("danger","品牌删除成功");
        return $this->redirect(["brand/index"]);
    }

//upload插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD*/
/*                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error' );
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                   // $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
/*                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath();*/ // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"

                //将图片上传到七牛运
                    //实例化七牛运
                    $qiniu = new Qiniu(\yii::$app->params['qiniu']);
                    //调用上传方法（文件绝对路径，名称）
                    $qiniu->uploadFile(
                        $action->getSavePath(),$action->getWebUrl()
                    );
                        //得到七牛运的地址
                    $url = $qiniu->getLink($action->getWebUrl());
                    //输出七牛运
                    $action->output['fileUrl'] =$url ;
                },
            ],
        ];
    }

    //测试七牛运文件上传
    public function actionQiniu()
    {

        $config = [
            'accessKey'=>'xCWkJrfaeXvST3sd7JJkNQp1rLEKy7AJKlLFWq4o',
            'secretKey'=>'JGN6HOJtw3vqi9s99Es8MXoQB7xlCs158OXfRD6m',
            'domain'=>'http://otc6tqfdn.bkt.clouddn.com/',
            'bucket'=>'yiishop',
            'area'=>Qiniu::AREA_HUADONG
        ];



        $qiniu = new Qiniu($config);
        $key = '/upload/5c/41/111.jpg';

        //将图片上传到七牛运
        $qiniu->uploadFile(\yii::getAlias('@webroot').'/upload/5c/41/111.jpg',$key);

        //获取该图片在七牛运的地址
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}























