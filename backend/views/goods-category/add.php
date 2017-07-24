<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


//加载静态资源
//加载css文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);

//$categories[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];

$zNodes = \yii\helpers\Json::encode(\backend\models\GoodsCategory::getZtreeNodes());
$zNodeId=$model->parent_id;
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
                //console.log(treeNode.id);
                //将选中节点的id赋值给parent_id
                //$(goods-category).val(treeNode.id);
                $("#goodscategory-parent_id").val(treeNode.id);
            }
	    }
    }
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$zNodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
               zTreeObj.expandAll(true);//展开所有节点
               //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", "{$zNodeId}", null);
    zTreeObj.selectNode(node);//选中当前节点的父节点


JS
);
$this->registerJs($js);


?>

