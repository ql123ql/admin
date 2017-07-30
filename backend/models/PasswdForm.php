<?php
namespace backend\models;

use yii\base\Model;

//class PasswdForm extends Model{
class PasswdForm extends \yii\db\ActiveRecord
{
    public $oldPassword;//旧密码
    public $newPassword;//新密码
    public $rePassword;//确认新密码
    public $password_hash;

    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword'],'required'],
            //添加自定义验证，验证旧密码是否正确
            ['oldPassword','validatePassword'],
            //新密码与旧密码不能一样
            ['newPassword','compare','compareAttribute'=>'oldPassword','operator'=>'!='],
            //确认密码和新密码要一样
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认密码',
        ];
    }
    //自定义验证方法
    public function validatePassword(){
        $passwordHash = \Yii::$app->user->identity->password_hash;
        $password = $this->oldPassword;
        if(!\Yii::$app->security->validatePassword($password,$passwordHash)){
            $this->addError('oldPassword','旧密码不正确');
        }
    }
}










