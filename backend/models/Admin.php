<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;


class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $pwd;
    public $code;
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username','email'],'required','on'=>['add','edit']],
            [['pwd'],'required','on'=>'add'],
            [['pwd'],'string','on'=>'edit'],
            [['email'],'email'],
            [['last_login_time','last_login_ip','created_at','updated_at'], 'integer'],
            [['password_hash','auth_key'], 'string', 'max' => 123],
            [['code'],'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'pwd' => '密码',
            'email' => '邮箱',
            'code'=>'验证码',
        ];
    }
    //保存之前执行的代码
    public function beforeSave($insert)
    {
        //只在添加的时候设置
        //$insert = $this->getIsNewRecord();
        if($insert){
            $this->created_at = time();

            $this->pwd = Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->auth_key = Yii::$app->security->generateRandomString();
        }else{
            if($this->pwd){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->pwd);
            }
        }
        $this->updated_at= time();

        return parent::beforeSave($insert);
    }

    //获取当前用户的菜单
    public function getMenus()
    {
        $menuItems = [];
        $menus = Menu::findAll(['parent_id'=>0]);
        foreach ($menus as $menu){
            $item = ['label'=>$menu->label,'items'=>[]];
            foreach ($menu->children as $child){
                //根据用户权限判断，该菜单是否显示
                if(Yii::$app->user->can($child->url)){
                    $item['items'][] = ['label'=>$child->label,'url'=>[$child->url]];
                }
            }
            //如果该一级菜单没有子菜单，就不显示
            if(!empty($item['items'])){
                $menuItems[] = $item;
            }
        }
        return $menuItems;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['auth_key'=>$token]);
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
