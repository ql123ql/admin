<h2>添加菜单</h2>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput();
echo $form->field($model,'url');
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getPermissions());
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();