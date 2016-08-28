<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "p2p_detail".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $platform_id
 * @property integer $type
 * @property string $amount
 * @property string $charge
 * @property integer $status
 * @property integer $time
 */
class Detail extends \yii\db\ActiveRecord {

    CONST TYPE_RECHARGE = 1;
    CONST TYPE_WITHDRAW = 2;

    CONST STATUS_ARRIVED = 1;
    CONST STATUS_NOT_ARRIVED = 2;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'p2p_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['platform_id', 'type', 'status', 'time'], 'integer'],
            [['amount', 'charge'], 'string', 'max' => 20]
        ];
    }

    /**
     * relation
     * @return \yii\db\ActiveQuery
     */
    public function getAccount() {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'platform_id' => 'Platform ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'charge' => 'Charge',
            'status' => 'Status',
            'time' => 'Time',
        ];
    }

    public static function getTypeList() {
        return [
            self::TYPE_RECHARGE => '充值',
            self::TYPE_WITHDRAW => '提现'
        ];
    }

    public static function getTypeByKey($key) {
        $result = '';
        $list = self::getTypeList();
        if (array_key_exists($key, $list)) {
            $result = $list[$key];
        }
        return $result;
    }

    public static function getStatusList() {
        return [
            self::STATUS_ARRIVED => '已到账',
            self::STATUS_NOT_ARRIVED => '未到账'
        ];
    }

    public static function getStatusByKey($key) {
        $result = '';
        $list = self::getStatusList();
        if (array_key_exists($key, $list)) {
            $result = $list[$key];
        }
        return $result;
    }

    /**
     * 充值提现后银行卡余额做相应变化
     * @param bool $insert
     * @return bool
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (isset($this->account->bankAccount)) {
            $bankAccount = $this->account->bankAccount;
            if ($this->type == self::TYPE_RECHARGE) {
                $bankAccount->balance -= $this->amount;
            } elseif ($this->type == self::TYPE_WITHDRAW) {
                $bankAccount->balance += $this->amount;
            }
            //int转string
            $bankAccount->balance = (string)$bankAccount->balance;
            $bankAccount->save();
        }
    }
}
