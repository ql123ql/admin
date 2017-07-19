<?php
/**
 * 这是文章的添加
 */

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name");
echo $form->field($model,"article_category_id")->dropDownList($model->articlecategoryoptions);
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"sort")->textInput(["type"=>"number"]);
echo $form->field($model,"status",["inline"=>true])->radioList(\backend\models\Article::getStatusOptions());
echo \yii\bootstrap\Html::submitButton("添加文章",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();