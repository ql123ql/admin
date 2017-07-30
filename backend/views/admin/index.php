<h2>管理员首页</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['admin/add'],['class'=>'btn btn-info']);
echo \yii\bootstrap\Html::a('登录',['admin/login'],['class'=>'btn btn-success']);
echo \yii\bootstrap\Html::a('修改自己密码',['admin/pas'],['class'=>'btn btn-info']);


?>
<table class="table">
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>最后登录时间</td>
        <td>最后登录Ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=date('Y-m-d H:i:s',$model->last_login_time)?></td>
        <td><?=$model->last_login_ip?></td>
        <td>
            <?php
            echo \yii\bootstrap\Html::a('修改',['admin/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            echo \yii\bootstrap\Html::a('删除',['admin/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
            echo \yii\bootstrap\Html::a('授权',['admin/rbac','id'=>$model->id],['class'=>'btn btn-success btn-xs']);

            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>