<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "borrow_detail".
 *
 * @property integer $id
 * @property integer $way_id
 * @property string $amount
 * @property string $remain
 * @property integer $borrow_time
 * @property integer $payment_time
 * @property integer $uid
 * @property integer $is_deleted
 */
class BorrowDetail extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'borrow_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['way_id', 'borrow_time', 'amount'], 'required'],
            [['id', 'way_id', 'borrow_time', 'payment_time', 'uid', 'is_deleted'], 'integer'],
            [['amount', 'remain'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'way_id' => 'Way ID',
            'amount' => 'Amount',
            'remain' => 'Remain',
            'borrow_time' => 'Borrow Time',
            'payment_time' => 'Payment Time',
            'uid' => 'Uid',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public function getWay() {
        return $this->hasOne(BorrowWay::className(), ['id' => 'way_id']);
    }

    public static function getDetailById($id) {
        return self::find()->where(['id' => $id, 'is_deleted' => 0])->one();
    }
}
