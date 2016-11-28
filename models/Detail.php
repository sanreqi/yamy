<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "p2p_detail".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $platform_id
 * @property integer $type
 * @property string $amount
 * @property string $charge
 * @property integer $status
 * @property integer $time
 */
class Detail extends \yii\db\ActiveRecord {

    CONST DETAIL_TABLE = 'p2p_detail';

    CONST TYPE_RECHARGE = 1;
    CONST TYPE_WITHDRAW = 2;

    CONST STATUS_ARRIVED = 1;
    CONST STATUS_NOT_ARRIVED = 2;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'p2p_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['platform_id', 'type', 'status', 'time'], 'integer'],
            [['amount', 'charge'], 'string', 'max' => 20]
        ];
    }

    /**
     * relation
     * @return \yii\db\ActiveQuery
     */
    public function getAccount() {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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

    /**
     * 充值提现后银行卡余额做相应变化
     * @param bool $insert
     * @return bool
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (isset($this->account->bankAccount)) {
            $bankAccount = $this->account->bankAccount;
            if ($this->type == self::TYPE_RECHARGE) {
                $bankAccount->balance -= $this->amount;
            } elseif ($this->type == self::TYPE_WITHDRAW) {
                $bankAccount->balance += $this->amount;
            }
            //int转string
            $bankAccount->balance = (string)$bankAccount->balance;
            $bankAccount->save();
        }
    }

    public static function getProfitsByPeriod1($startTime, $endTime) {
        //提现总额-充值总额+最后一笔提现总额余额+返现总额
        $query = new Query();
        $query
            ->select(['SUM(AMOUNT) AS sum'])
            ->from('p2p_detail')
            ->where(['is_deleted' => 0])
            ->andWhere(['BETWEEN', 'time', $startTime, $endTime]);
        $query1 = clone $query;
        $query2 = clone $query;
        $row1 = $query1->andWhere(['type' => 1])->one();
        $row2 = $query2->andWhere(['type' => 2])->one();
        //GROUP BY account_id取最大id的current_balance的和
        $sql1 = 'SELECT SUM(current_balance) AS sum FROM p2p_detail AS SUM WHERE id in (SELECT MAX(id) FROM p2p_detail WHERE is_deleted=0 AND type=2 AND time BETWEEN ' . $startTime . ' AND ' . $endTime . ' GROUP BY account_id)';
        $row3 = Yii::$app->db->createCommand($sql1)->queryOne();
        $sql2 = '
          SELECT SUM(current_balance) AS sum FROM p2p_detail WHERE id in (
            SELECT MAX(id) FROM(
              SELECT * FROM (
                SELECT b.id,b.account_id,a.max_id FROM (
                  SELECT MAX(id) AS max_id, account_id from p2p_detail WHERE is_deleted=0 AND type=2 AND time BETWEEN ' . $startTime . ' AND ' . $endTime . ' GROUP BY account_id
                  ) a INNER JOIN p2p_detail b ON a.account_id=b.account_id
                ) c WHERE c.id<c.max_id
            ) d GROUP BY d.account_id
          )
        ';
        $row4 = Yii::$app->db->createCommand($sql2)->queryOne();

        //提现
        $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
        //充值
        $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
        //余额
        $currentBalance = isset($row3['sum']) ? round($row3['sum'], 2) : 0;
        $beforeBalance = isset($row4['sum']) ? round($row4['sum'], 2) : 0;

        $query = new Query();
        $row5 = $query
            ->select(['SUM(AMOUNT) AS sum'])
            ->from('p2p_cashback')
            ->where(['is_deleted' => 0])
            ->andWhere(['BETWEEN', 'time', $startTime, $endTime])
            ->one();
        //返现
        $cashback = isset($row5['sum']) ? round($row5['sum'], 2) : 0;
        //收益

        echo "withdraw:" . $withdraw . "<br/>";
        echo "recharge:" . $recharge . "<br/>";
        echo "currentbalance:" . $currentBalance . "<br/>";
        echo "cashback:" . $cashback . "<br/>";
        echo "beforebalance:" . $beforeBalance . "<br/>";
        $profit = $withdraw - $recharge + $currentBalance + $cashback - $beforeBalance;
        return $profit < 0 ? 0 : $profit;
    }

    public static function getProfitsByPeriod($startTime, $endTime) {
        $profit = 0;
        $query1 = new Query();
        $withdraw = $query1
            ->select(['id', 'account_id', 'current_balance', 'amount'])
            ->from('p2p_detail')
            ->where(['is_deleted' => 0, 'type' => 2])
            ->andWhere(['BETWEEN', 'time', $startTime, $endTime])
            ->all();
        if (!empty($withdraw)) {
            foreach ($withdraw as $w) {
                $beforeBalance = 0;
                $query2 = new Query();
                $row2 = $query2
                    ->select(['current_balance','id'])
                    ->from('p2p_detail')
                    ->where(['account_id' => $w['account_id'], 'is_deleted' => 0])
                    ->andWhere(['<', 'id', $w['id']])
                    ->orderBy('id DESC')
                    ->one();
                if ($row2['current_balance']) {
                    $beforeBalance = $row2['current_balance'];
                }
                $profit += $w['amount'] + $w['current_balance'] - $beforeBalance;
            }
        }
        //返现
        $rowCashback = (new Query())
            ->select(['SUM(AMOUNT) AS sum'])
            ->from('p2p_cashback')
            ->where(['is_deleted' => 0])
            ->andWhere(['BETWEEN', 'time', $startTime, $endTime])
            ->one();
        //返现
        $cashback = isset($rowCashback['sum']) ? round($rowCashback['sum'], 2) : 0;
        $profit = $profit + $cashback;
        return $profit <= 0 ? 0 : round($profit, 2);
    }
}
