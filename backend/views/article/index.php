
<!--1
1.完成用户注册功能(使用admin表),需要验证码,用户头像,姓名,年龄,性别...
2.完成用户(admin)登录功能
3.添加TestController,和4个操作add edit index del.
使用ACF过滤器设置权限,只有登录用户才可以执行add edit del操作
,登录和未登录用户都可以执行index操作

-->
<?=\yii\bootstrap\Html::a("添加",["article/add"],["class"=>"btn btn-info btn-sm"])?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>分类</th>
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
//echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);
echo \yii\widgets\LinkPager::widget(["pagination"=>$pager,"nextPageLabel"=>"下一页","prevPageLabel"=>"上一页","firstPageLabel"=>"首页"]);