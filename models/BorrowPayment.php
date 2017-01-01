<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "borrow_payment".
 *
 * @property integer $id
 * @property integer $detail_id
 * @property integer $way_id
 * @property string $amount
 * @property string $interest
 * @property integer $time
 * @property integer $uid
 * @property integer $is_deleted
 */
class BorrowPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'borrow_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid'], 'required'],
            [['id', 'detail_id', 'way_id', 'time', 'uid', 'is_deleted'], 'integer'],
            [['amount', 'interest'], 'string', 'max' => 20]
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
            'way_id' => 'Way ID',
            'amount' => 'Amount',
            'interest' => 'Interest',
            'time' => 'Time',
            'uid' => 'Uid',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
