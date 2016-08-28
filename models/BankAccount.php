<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_account".
 *
 * @property integer $id
 * @property string $username
 * @property string $truename
 * @property string $card
 * @property string $reserved_phone
 * @property string $bank
 * @property string $balance
 */
class BankAccount extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'bank_account';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'truename', 'card', 'reserved_phone', 'bank', 'balance'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'truename' => 'Truename',
            'card' => 'Card',
            'reserved_phone' => 'Reserved Phone',
            'bank' => 'Bank',
            'balance' => 'Balance',
        ];
    }

    /**
     * 个人信息,下拉框用
     * @return array
     */
    public static function getDisplayOptions() {
        $result = [];
        $options = BankAccount::find()->where(['is_deleted' => 0])->asArray()->all();
        if (!empty($options)) {
            foreach ($options as $v) {
                $result[$v['id']] = $v['reserved_phone'].'/'.$v['truename'].'/'.$v['card'].'/'.$v['bank'];
            }
        }
        return $result;
    }

    /**
     * 以尾号4位作为唯一性，有可能会重复，小概率事件。。。
     * @param $card
     * @return int|mixed
     */
    public static function getByCard($card) {
        return BankAccount::find()->where(['card' => $card])->asArray()->one();
    }
}
