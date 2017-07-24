<?=\yii\bootstrap\Html::a("添加文章分类",["article-category/add"],["class"=>"btn btn-info btn-sm"])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article): ?>
        <tr>
            <td><?=$article->id ?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->statustext?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$article->id],['class'=>'btn btn-sm btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$article->id],['class'=>'btn btn-sm btn-success'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);
