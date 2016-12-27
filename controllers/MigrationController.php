<?php
/**
 * User: srq
 * Date: 2016/8/28
 * Time: 8:19
 */

namespace app\controllers;


use app\models\Account;
use app\models\BankAccount;
use yii\db\Query;
use yii\web\Controller;
use Yii;

class MigrationController extends Controller {

    private $detailTable = 'p2p_detail';
    private $accountTable = 'p2p_account';

    /*
    public function actionDoPersonalInfo() {
        $bankAccount = BankAccount::find()->where(['is_deleted' => 0])->asArray()->all();
        if (!empty($bankAccount)) {
            foreach ($bankAccount as $v) {
                Account::updateAll(['bankaccount_id' => $v['id']], 'mobile="'.$v['reserved_phone'].'"');
            }
        }
    }
    */

    /*
    public function actionDoDetail() {
        $db = Yii::$app->db;
        $query1 = new Query();
        $query2 = new Query();
        $accounts = $query1->select(['id'])->from($this->accountTable)->where(['is_deleted'=>0])->all();
        foreach ($accounts as $account) {
            $balance = 0;
            $accountId = $account['id'];
            $details = $query2->from($this->detailTable)->where(['account_id'=>$accountId,'is_deleted'=>0])->orderBy('id')->all();
            if (!empty($details)) {
                foreach ($details as $detail) {
                    $type = $detail['type'];
                    $amount = $detail['amount'];
                    if ($type == 1) {
                        $balance += $amount;
                        //充值
                    } elseif ($type == 2) {
                        //提现
                        $balance -= $amount;
                        if ($balance <= 0) {
                            $balance = 0;
                        }
                    }
                    $db->createCommand()->update($this->detailTable, ['current_balance'=>$balance], ['id'=>$detail['id']])->execute();
                }
            }
        }
    }
    */

    public function actionT() {
        //detail分组id最大的currentbalance与此account的balance不相等
        $result = [];
        $rows1 = (new Query())
            ->select(['id', 'account_id', 'current_balance'])
            ->from($this->detailTable)
            ->where(['is_deleted' => 0])
            ->orderBy('id DESC')
            ->groupBy('account_id')
            ->createCommand()
            ->getRawSql();
//            ->all();
     echo $rows1; exit;
        $sql = 'select * from (select * from p2p_detail order by id desc) as a group by a.account_id';
        $rows1 = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($rows1 as $d) {
            $rows = (new Query())
                ->select('*')
                ->from($this->accountTable)
                ->where(['is_deleted' => 0, 'id' => $d['account_id'], 'balance' => $d['current_balance']])
                ->one();
            if (empty($rows)) {
                $result[] = $d['id'];
            }
        }
        print_r($result);
    }
}