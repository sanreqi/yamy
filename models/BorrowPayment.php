<?php

namespace app\models;

use Yii;
use yii\db\Query;

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
class BorrowPayment extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'borrow_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'remain'], 'required'],
            [['id', 'detail_id', 'way_id', 'time', 'uid', 'is_deleted'], 'integer'],
            [['amount', 'interest'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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

    /**
     * relation
     * @return \yii\db\ActiveQuery
     */
    public function getDetail() {
        return $this->hasOne(BorrowWay::className(), ['id' => 'detail_id']);
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findModelById($id) {
        return self::find()->where(['id' => $id, 'is_deleted' => 0])->one();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPaymentsByDetailId($id) {
        return self::find()->where(['detail_id' => $id, 'is_deleted' => 0])->all();
    }

    /**
     * 根据detailid获取总利息
     * @param $detailId
     * @return int
     */
    public static function getInterestByDetailId($detailId) {
        $query = new Query();
        $result = $query->select(['SUM(interest) AS sum'])->from(BorrowPayment::tableName())->where([
            'is_deleted' => 0, 'detail_id' => $detailId])->one();
        return !empty($result) ? $result['sum'] : 0;
    }

    /**
     * 根据uid获取总利息
     * @param $uid
     * @return int
     */
    public static function getInterestByUid($uid) {
        $query = new Query();
        $result = $query->select(['SUM(interest) AS sum'])->from(BorrowPayment::tableName())->where([
            'is_deleted' => 0, 'uid' => $uid])->one();
        return !empty($result) ? $result['sum'] : 0;
    }


}
