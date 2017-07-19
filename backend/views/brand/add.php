<?php
/**
 * 这是品牌的添加
 */
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name");
echo $form->field($model,"intro")->textarea();
//echo $form->field($model,"imgFile")->fileInput();

//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
    }
}
EOF
        ),
    ]
]);


echo $form->field($model,"sort")->textInput(["type"=>"number"]);
echo $form->field($model,"status",["inline"=>true])->radioList(\backend\models\Brand::getStatusOptions());
echo \yii\bootstrap\Html::submitButton("添加品牌",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();