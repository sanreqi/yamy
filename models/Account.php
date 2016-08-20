<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "p2p_account".
 *
 * @property integer $id
 * @property integer $platform_id
 * @property string $truename
 * @property string $idcard
 * @property string $username
 * @property string $mobile
 * @property string $login_passwd
 * @property string $pay_passwd
 * @property integer $regis_time
 * @property string $balance
 */
class Account extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'p2p_account';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //            [['platform_id', 'truename', 'idcard', 'username', 'mobile', 'login_passwd', 'pay_passwd', 'regis_time', 'balance'], 'required'],
            [['platform_id', 'regis_time'], 'integer'],
            [['truename', 'username'], 'string', 'max' => 32],
            [['idcard'], 'string', 'max' => 24],
            [['mobile', 'login_passwd', 'pay_passwd', 'balance'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'platform_id' => 'Platform ID',
            'truename' => 'Truename',
            'idcard' => 'Idcard',
            'username' => 'Username',
            'mobile' => 'Mobile',
            'login_passwd' => 'Login Passwd',
            'pay_passwd' => 'Pay Passwd',
            'regis_time' => 'Regis Time',
            'balance' => 'Balance',
        ];
    }

    public function getPlatform() {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }

    //pid是platfromid不是parentid!
    public static function getAccountsByPid($platformId) {
        $result = [];
        $models = Account::find()->where(['platform_id' => $platformId, 'is_deleted' => 0])->asArray()->all();
        if (!empty($models)) {
            foreach ($models as $model) {
                $result[$model['id']] = $model['mobile'];
            }
        }
        return $result;
    }

    public static function getMobileById($id) {
        $model = Account::find()->where(['id' => $id])->one();
        return isset($model['mobile']) ? $model['mobile'] : '';
    }

    public static function getMobileOptions() {
        $result = [];
        $models = Account::find()->where(['is_deleted' => 0])->groupBy(['mobile'])->all();
        if (!empty($models)) {
            if (!empty($models)) {
                foreach ($models as $model) {
                    if (!empty($model['mobile'])) {
                        $result[$model['mobile']] = $model['mobile'];
                    }
                }
            }
        }
        return $result;
    }

    public static function getAccountById($id) {
        return Account::find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * 个人信息下拉框
     */
    public static function getInfoOptions() {
        $result = [];
        $list = ConstData::getPersonalInfoList();
        foreach ($list as $k => $v) {
            $result[$k] = $v['mobile'] . '/' . $v['username'] . '/' . $v['bankcard'] . '/' . $v['banktype'];
        }
        return $result;
    }

}
