<?php

namespace app\models;

use Yii;
use yii\db\Query;

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

    public function getBankAccount() {
        return $this->hasOne(BankAccount::className(), ['id' => 'bankaccount_id']);
    }

    public function getSim() {
        return $this->hasOne(Sim::className(), ['id' => 'sim_id']);
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

    /**
     * 获取某个账号收益
     * @param $id
     * @return bool|float|mixed
     */
    public static function getProfitById($id) {
        $account = Account::getAccountById($id);
        if (!empty($account)) {
            $query = new Query();
            $row1 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_RECHARGE])->one();
            $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_WITHDRAW])->one();
            $row3 = $query->select(['sum(c.amount) as sum'])->from('p2p_cashback c')->innerJoin(['d' => 'p2p_detail'], 'c.detail_id=d.id')->where(['d.is_deleted' => 0, 'c.is_deleted' => 0, 'd.account_id' => $id])->one();
            $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
            $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
            $cashback = isset($row3['sum']) ? round($row3['sum'], 2) : 0;
            $profit = $account['balance'] - ($recharge - $withdraw) + $cashback;
            $profit = round($profit, 2);
            return $profit;
        }
        return false;
    }

    /**
     * 如果余额为0就把最近回款事件改为0
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->balance == 0) {
                $this->returned_time = 0;
            }
            return true;
        } else {
            return false;
        }
    }

}
