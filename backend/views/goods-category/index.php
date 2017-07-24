<?=\yii\bootstrap\Html::a("添加商品分类",["goods-category/add"],["class"=>"btn btn-info btn-sm"])?>

    <table class="cate table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model['id']?></td>
            <td><?=str_repeat('—',$model['depth']).$model['name']?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model['id']],['class'=>'btn btn-sm btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model['id']],['class'=>'btn btn-sm btn-success'])?>

            </td>
        </tr>
    <?php endforeach;?>
</table>

























