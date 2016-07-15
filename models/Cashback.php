<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "p2p_cashback".
 *
 * @property integer $id
 * @property integer $detail_id
 * @property integer $platform
 * @property integer $amount
 * @property integer $type
 * @property integer $status
 * @property integer $time
 */
class Cashback extends \yii\db\ActiveRecord
{
    CONST TYPE_ZHIFUBAO = 1;
    CONST TYPE_WEIXIN = 2;
    
    CONST STATUS_ARRIVED = 1;
    CONST STATUS_NOT_ARRIVED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'p2p_cashback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['detail_id', 'platform', 'amount', 'type', 'status', 'time'], 'required'],
            [['detail_id', 'amount', 'type', 'status', 'time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail_id' => 'Detail ID',
            'platform' => 'Platform',
            'amount' => 'Amount',
            'type' => 'Type',
            'status' => 'Status',
            'time' => 'Time',
        ];
    }
    
    public static function getTypeList() {
        return [
            self::TYPE_ZHIFUBAO => '支付宝',
            self::TYPE_WEIXIN => '微信'
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
