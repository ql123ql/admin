<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput(['id'=>'logo']);
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo \yii\helpers\Html::img(
    $model->logo?$model->logo:false,
    ['id'=>'img-logo','height'=>'50']
);
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Brand::find()->all(),'id','name'),['prompt'=>'请选择分类']);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale',['inline'=>true])->radioList([0=>'下架',1=>'在售']);
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'回收站',1=>'正常']);
echo $form->field($model,'sort');
echo $form->field($detail,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        'serverUrl'=>\yii\helpers\Url::to(['u-upload']),
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'zh-cn', //中文为 zh-cn
        'toolbars'=> [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|','simpleupload','imagecenter','insertimage','emotion'
            ]
        ],
    ]
]);


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

//加载静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($categories);
$js=new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                console.log(treeNode.id);
                //将选中节点的id赋值给parent_id
                var re = $('#goods-goods_category_id').val(treeNode.id);
// console.log(re);
            }
	    }
    }
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$zNodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
               zTreeObj.expandAll(true);//展开所有节点
JS
);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
         //将上传成功后的图片地址(data.fileUrl)写入logo字段
        $("#logo").val(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签 展示图片
        $("#img-logo").attr("src",data.fileUrl);

    }
}
EOF
        ),
    ]
]);
$this->registerJs($js);




