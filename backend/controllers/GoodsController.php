<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\SearchForm;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use backend\filters\RbacFilter;

class GoodsController extends \yii\web\Controller
{
    //商品展示
    public function actionIndex()
    {
        //实例化搜索表单模型
        $model = new SearchForm();
        //只显示状态为1的商品
        $query = Goods::find()->where(['status'=>1]);
        //实例化提交方式
        $request = new Request();
        //判断是否是get方式提交
        if($request->isGet){
            //接受并验证
            if($model->load($request->get()) && $model->validate()){
                //调用search方法
                $model->search($query);
            }
        }
        //实例化分页组件
        $pager = new Pagination([
            //查询出数据库所有条数
            'totalCount'=>$query->count(),
            //指定每页显示几条
            'pageSize'=>2,
        ]);
        //查询出数据
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //将查询后的数据分配到index页面
        return $this->render('index',['models'=>$models,'model'=>$model,'pager'=>$pager]);
    }

    //商品添加
    public function actionAdd(){
        $model=new Goods();
        $detail = new GoodsIntro();

        if($model->load(\Yii::$app->request->post())&& $detail->load(\Yii::$app->request->post())&& $model->validate()&& $detail->validate()) {

            $day = date('Y-m-d', time());
            //根据日期查询商品数量表，如果有记录就+1  没有就新增一条
            if (GoodsDayCount::find()->select('*')->where(['day' => $day])->count() == 0) {
                //添加新纪录
                $day_count = new GoodsDayCount();
                $day_count->day = $day;
                $day_count->count = 1;
                $day_count->save();
            } else {
                //修改记录+1
                $count = GoodsDayCount::findOne(['day' => $day]);
                $count->count += 1;
                $count->save();
            }
            //添加时间
            $model->create_time = time();
            //添加货号（新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001）
            //当前年月日
            $ydm = date('Ymd', time());//当前年月日
            //如果没有当天的记录，说明今天还没有添加过商品，创建一条记录，count设置为0；如果有记录，则获取该记录的count值。
            $sum = GoodsDayCount::find()->select(['count'])->where(['day' => date('Y-m-d', time())])->column()[0];
            //补0固定五位数
            $sum2 = str_pad($sum, 5, '0', STR_PAD_LEFT);
            //拼接商品货号
            $model->sn = $ydm . '' . $sum2;
            //保存数据
            $model->save();
            $detail->goods_id = $model->id;
            $detail->save();
            \Yii::$app->session->setFlash('success', '添加成功');
            //成功后跳转index页面
            return $this->redirect(['goods/index']);
        }
        //查询出商品分类列表
        $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
        //将所需数据分配给add视图
        return $this->render('add',['model'=>$model,'detail'=>$detail,'categories'=>$categories]);
    }

    //商品修改
    public function actionEdit($id){
        //根据id查询数据将其修改
        $model = Goods::findOne(['id'=>$id]);
        $detail = GoodsIntro::findOne(['goods_id'=>$id]);
        //实例化提交方式
        $request = \Yii::$app->request;
        //判断是否post方式提交
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证数据
            if($model->validate() && $detail->validate()){
                //得到当前时间戳
                $model->create_time = time();
                //将数据进行保存
                if($model->save()){
                    $detail->save(false);
                }
                //添加成功跳转index页面,失败则打印错误信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //查询出商品分类数据列表
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        //将所需数据分配给修改页面
        return $this->render('add',['model'=>$model,'detail'=>$detail,'categories'=>$categories]);
    }

    //逻辑删除
    public function actionDel($id){
        //根据id查出对应的数据将其修改为删除状态
        $model=Goods::findOne(["id"=>$id]);
        $detail = GoodsIntro::findOne(['goods_id'=>$id]);
        //统一将商品状态修改为0并保存数据库
        $model->is_on_sale=0;
        $model->status=0;
        $model->save();
        //跳转index显示页面
        return $this->redirect(["goods/index"]);
    }

//upload插件
    public function actions() {
        return [
            'u-upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "",//图片访问路径前缀׺
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif','bmp'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //调用七牛云组件,将图片上传到七牛云
                    $qiniu = new Qiniu(\yii::$app->params['qiniu']);
                    //$qiniu = \Yii::$app->qiniu;
                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl());
//                    //获取该图片的在七牛云的地址
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']=$url;

//                //图片上传成功的同时，将图片和商品关联起来
//                $model = new GoodsPhoto();
//                $model->goods_id = \Yii::$app->request->post('goods_id');
//                $model->path = $action->getWebUrl();
//                $model->save();
//                $action->output['fileUrl'] = $model->path;
                },
            ],
        ];
    }
    //根据Id查看商品详情
    public function actionSel($id){
        //根据id显示相关商品的详情
        $model = Goods::findOne(['id'=>$id]);
        $detail = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('sel',['model'=>$model,'detail'=>$detail]);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }


}
