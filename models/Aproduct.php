<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yamy_aproduct".
 *
 * @property integer $id
 * @property integer $invid
 * @property string $invname
 * @property string $name
 * @property integer $catid
 * @property string $code
 * @property integer $total
 * @property integer $minpay
 * @property string $validity
 * @property integer $interest
 * @property integer $deadline
 * @property integer $repay_type
 * @property integer $borrow_type
 * @property integer $value_date
 * @property integer $createtime
 * @property integer $updatetime
 */
class Aproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yamy_aproduct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invid', 'catid', 'total', 'minpay', 'interest', 'deadline', 'repay_type', 'borrow_type', 'value_date', 'createtime', 'updatetime'], 'integer'],
            [['invname', 'name'], 'string', 'max' => 32],
            [['code', 'validity'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invid' => 'Invid',
            'invname' => 'Invname',
            'name' => 'Name',
            'catid' => 'Catid',
            'code' => 'Code',
            'total' => 'Total',
            'minpay' => 'Minpay',
            'validity' => 'Validity',
            'interest' => 'Interest',
            'deadline' => 'Deadline',
            'repay_type' => 'Repay Type',
            'borrow_type' => 'Borrow Type',
            'value_date' => 'Value Date',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }
}
