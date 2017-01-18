<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "landmine".
 *
 * @property integer $id
 * @property integer $detail_id
 * @property string $platform
 * @property string $account
 * @property string $amount
 */
class Landmine extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'landmine';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['detail_id'], 'integer'],
            [['platform', 'account', 'amount'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'detail_id' => 'Detail ID',
            'platform' => 'Platform',
            'account' => 'Account',
            'amount' => 'Amount',
        ];
    }

    public static function getTotal() {
        $query = new Query();
        $result = $query->select(['SUM(amount) AS total'])->from(Landmine::tableName())->one();
        return $result['total'] ? $result['total'] : 0;
    }
}
