<?=\yii\bootstrap\Html::a("添加商品",["goods/add"],["class"=>"btn btn-info btn-sm"])?>
<?php
$form=\yii\bootstrap\ActiveForm::begin([
    'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品分类id</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><img src="<?=$model->logo?>" width="30px" height="30px"></td>
            <td><?=$model->goods_category_id?$model->cate_gory->name:''?></td>
            <td><?=$model->brand_id?$model->brand->name:''?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\backend\models\Goods::$is_on_saleOptions[$model->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$statusOptions[$model->status]?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Y-d-m H:i:s',$model->create_time)?></td>
            <td>
                <?php
                echo \yii\bootstrap\Html::a('查看',['goods/sel','id'=>$model->id],['class'=>'btn btn-info btn-xs']);
                echo \yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
echo \yii\widgets\LinkPager::widget(["pagination"=>$pager,"nextPageLabel"=>"下一页","prevPageLabel"=>"上一页","firstPageLabel"=>"首页"]);
?>























