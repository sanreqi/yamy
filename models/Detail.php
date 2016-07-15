<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "p2p_detail".
 *
 * @property integer $id
 * @property integer $platform_id
 * @property integer $type
 * @property string $amount
 * @property string $charge
 * @property integer $status
 * @property integer $time
 */
class Detail extends \yii\db\ActiveRecord
{
    
    CONST TYPE_RECHARGE = 1;
    CONST TYPE_WITHDRAW = 2;
    
    CONST STATUS_ARRIVED = 1;
    CONST STATUS_NOT_ARRIVED = 2;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'p2p_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['platform_id', 'type', 'amount', 'charge', 'status', 'time'], 'required'],
            [['platform_id', 'type', 'status', 'time'], 'integer'],
            [['amount', 'charge'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
}
