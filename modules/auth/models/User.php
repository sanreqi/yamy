<?php

namespace app\modules\auth\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $truename
 * @property integer $createtime
 * @property integer $updatetime
 * @property integer $last_logintime
 * @property integer $is_deleted
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    public $passwordConfirm;
    /*表单接收到未加密的密码*/
    public $pwd;
    /*角色*/
    public $roles = [];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'createtime', 'updatetime', 'last_logintime', 'is_deleted'], 'integer'],
            ['username', 'string', 'max' => 10, 'min' => 3, 'tooLong' => '用户名必须3-10个字符', 'tooShort' => '用户名必须3-10个字符'],
            ['username', 'unique', 'message' => '用户名已存在'],
            ['truename', 'string', 'max' => 10, 'min' => 2, 'tooLong' => '姓名必须2-10个字符', 'tooShort' => '姓名必须2-10个字符'],
            ['pwd', 'string', 'min' => 6, 'max' => 16, 'tooLong' => '密码长度为6-16位', 'tooShort' => '密码长度为6-16位', 'on' => ['create','update']],
            ['username', 'required', 'message' => '用户名不能为空'],
            ['truename', 'required', 'message' => '姓名不能为空'],
            ['pwd', 'required', 'message' => '密码不能为空', 'on' => 'create'],
            ['passwordConfirm', 'required', 'message' => '密码确认不能为空', 'on' => 'create'],
            ['passwordConfirm', 'validatePwdConfirm', 'on' => ['create','update']],
            ['roles', 'safe']
        ];
    }

    public function validatePwdConfirm() {
        if ($this->pwd != $this->passwordConfirm) {
            $this->addError('passwordConfirm', '两次密码不一致');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'pwd' => '密码',
            'passwordConfirm' => '密码确认',
            'truename' => '真实姓名',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
            'last_logintime' => 'Last Logintime',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public static function getEncryptPwd($pwd) {
        return md5($pwd . Yii::$app->params['salt']);
    }

    public static function findByUsername($username) {
        return User::find()->where(['username' => $username, 'is_deleted' => 0])->one();
    }

    public static function findIdentity($id) {
        return User::find()->where(['id' => $id])->one();
    }


    public static function findIdentityByAccessToken($token, $type = null) {
        return User::find()->where(['accessToken' => $token])->one();
    }


    public function getId() {
        return $this->id;
    }


    public function getAuthKey() {
        return $this->authKey;
    }


    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    public static function getSelfRoles() {
        $auth = Yii::$app->authManager;
        return array_keys($auth->getRolesByUser(Yii::$app->user->id));
    }

}
