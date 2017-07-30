<?php
namespace backend\models;

use backend\models\Admin;
use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $rememberMe = true;//记住密码

    public function rules(){
        return [
            [['username','password'],'required'],
            //添加自定义验证
            ['username','validateUsername']
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }
    //自定义验证方法
    public function validateUsername(){
        $table = Admin::findOne(['username'=>$this->username]);
       //var_dump();exit;
        if($table){
//            if($this->password != $table->password){
            if(\Yii::$app->security->validatePassword($this->password,$table->password_hash)){
                //账号密码正确登录
                //$table -> generateAuthKey();
                $table->last_login_time = time();
                $table->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $table->save();
                //登录（保存用户到信息到session）
                \Yii::$app->user->login($table,$this->rememberMe ? 3600 * 24 * 30 : 0);
                //密码错误,提示错误信息
            }else{
                $this->addError('password','密码不正确');
            }
        }else{
            //用户名不存在,提示错误信息
            $this->addError('username','用户名不存在');
        }
    }
}



















