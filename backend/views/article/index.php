<?=\yii\bootstrap\Html::a("添加",["article/add"],["class"=>"btn btn-info btn-sm"])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>分类ID</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article): ?>
        <tr>
            <td><?=$article->id ?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->article_category_id?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->statustext?></td>
            <td><?= date("Y-m-d H:i:s",$article->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-sm btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id],['class'=>'btn btn-sm btn-success'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(["pagination"=>$pager,"nextPageLabel"=>"下一页","prevPageLabel"=>"上一页","firstPageLabel"=>"首页"]);
?>

