<?=\yii\bootstrap\Html::a("添加",["brand/add"],["class"=>"btn btn-info btn-sm"])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand): ?>
        <tr>
            <td><?=$brand->id ?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img( $brand->logo,["height"=>60]) ?></td>
            <td><?=$brand->sort?></td>
            <td><?=$brand->statustext?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-sm btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-sm btn-success'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);
?>