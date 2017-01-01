<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "borrow_way".
 *
 * @property integer $id
 * @property string $platform
 * @property string $account
 * @property integer $type
 * @property string $rate
 * @property string $remain
 * @property integer $uid
 * @property integer $is_deleted
 */
class BorrowWay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'borrow_way';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'uid', 'is_deleted'], 'integer'],
            [['platform', 'account', 'rate', 'remain'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform' => 'Platform',
            'account' => 'Account',
            'type' => 'Type',
            'rate' => 'Rate',
            'remain' => 'Remain',
            'uid' => 'Uid',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
