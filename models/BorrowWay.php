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
class BorrowWay extends \yii\db\ActiveRecord {

    CONST TYPE_ANYTIME = 1;
    CONST TYPE_AVERAGE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'borrow_way';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['platform', 'account'], 'required'],
            [['type', 'uid', 'is_deleted'], 'integer'],
            [['platform', 'account', 'rate', 'remain'], 'string', 'max' => 20],
            [['note'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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

    public static function getTypeList() {
        return [
            self::TYPE_ANYTIME => '随借随还',
            self::TYPE_AVERAGE => '等额本息',
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

    public static function findModelById($id) {
        return BorrowWay::find()->where(['id' => $id, 'is_deleted' => 0])->one();
    }

    /**
     * 获取本人借款渠道
     * @return array
     */
    public static function getWayOptions() {
        $result = [];
        $rows = BorrowWay::find()->where(['uid' => Yii::$app->user->id, 'is_deleted' => 0])->asArray()->all();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $result[$row['id']] = $row['platform'] . '--' . $row['account'];
            }
        }
        return $result;
    }
}
