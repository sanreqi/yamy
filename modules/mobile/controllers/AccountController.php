<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 11:24
 */

namespace app\modules\mobile\controllers;


use app\models\Account;
use app\models\BankAccount;
use app\models\Cashback;
use app\models\Detail;
use app\models\Platform;
use yii\db\Query;
use Yii;

class AccountController extends TController {

    private $accountTable = 'p2p_account';
    private $platformTable = 'p2p_platform';

    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 创建页面
     * @return string
     */
    public function actionCreate() {
        $platformOptions = Platform::getOptions();
        $bankAccountOptions = BankAccount::getDisplayOptions();
        return $this->render('create', [
            'platformOptions' => $platformOptions,
            'bankAccountOptions' => $bankAccountOptions
        ]);
    }

    /**
     * 创建账号，若要创建明细和返现则相关信息都为必填
     * @return object
     */
    public function actionCreateAjax() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        $platformId = $data['platform_id'];
        $bankAccountId = $data['bank_account_id'];
        $bankAccount = BankAccount::getModelById($bankAccountId);
        if (!empty($bankAccount)) {
            //验证账号是否已存在
            $mobile = $bankAccount['reserved_phone'];
            $account = Account::find()->where(['platform_id' => $platformId, 'mobile' => $mobile])->one();
            if (!empty($account)) {
                return $this->ajaxResponseError("账号已存在");
            }

            $p2pAccount = new Account();
            $p2pAccount->platform_id = $platformId;
            $p2pAccount->bankaccount_id = $bankAccountId;
            $p2pAccount->mobile = $mobile;
            $p2pAccount->truename = $bankAccount['truename'];
            $p2pAccount->balance = $data['balance'];
            $p2pAccount->is_deleted = 0;
            $p2pAccount->returned_time = strtotime($data['returned_date']);
            if ($p2pAccount->save()) {
                if (!empty($data['recharge_amount']) && !empty($data['recharge_date'])) {
                    $detail = new Detail();
                    //充值
                    $detail->type = Detail::TYPE_RECHARGE;
                    $detail->platform_id = $platformId;
                    $detail->account_id = $p2pAccount->id;
                    $detail->amount = $data['recharge_amount'];
                    $detail->current_balance = $p2pAccount->balance;
                    $detail->time = !empty($data['recharge_date']) ? strtotime($data['recharge_date']) : 0;
                    if ($detail->save()) {
                        if (!empty($data['cashback_amount']) && !empty($data['cashback_name']) && !empty($data['cashback_date'])) {
                            $cachback = new Cashback();
                            $cachback->detail_id = $detail->id;
                            $cachback->account_id = $p2pAccount->id;
                            $cachback->platform = Platform::getNameById($p2pAccount->platform_id);
                            $cachback->amount = $data['cashback_amount'];
                            $cachback->casher = $data['cashback_name'];
                            $cachback->type = Cashback::TYPE_ZHIFUBAO;
                            $cachback->status = Cashback::STATUS_NOT_ARRIVED;
                            $cachback->time = !empty($data['cashback_date']) ? strtotime($data['cashback_date']) : 0;
                            $cachback->save();
                        }
                    }
                }
                return $this->ajaxResponseSuccess(['id' => $p2pAccount->id]);
            }
            return $this->ajaxResponseError('创建失败');
        }
    }

    /**
     * 账户列表页面搜索结果返回
     * @return object
     */
    public function actionGetAccountList() {
        $this->checkIsAjaxRequestAndResponse();
        $keyword = Yii::$app->request->get("keyword");
        if (empty($keyword)) {
            return $this->ajaxResponseError("请输入关键字");
        }
        $query = new Query();
        $result = $query
            ->select(['a.id', 'a.mobile', 'p.name'])
            ->from($this->accountTable . ' a')
            ->innerJoin(['p' => $this->platformTable], 'a.platform_id=p.id')
            ->where(['p.is_deleted' => 0, 'a.is_deleted' => 0])
            ->andWhere(['LIKE', 'p.name', $keyword])
            ->all();
        return $this->ajaxResponseSuccess($result);
    }

    /**
     * 账户详情页
     * @return string
     */
    public function actionView() {
        $id = Yii::$app->request->get("id");
        if (empty($id)) {
            echo "PAGE NOT EXISTS!";
            exit;
        }
        $id = Yii::$app->request->get('id', 0);
        $account = Account::getAccountById($id);
        if ($account) {
            $platformName = Platform::getNameById($account['platform_id']);
            $balance = $account['balance'];
            $query = new Query();
            $row1 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_RECHARGE])->one();
            $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_WITHDRAW])->one();
            $row3 = $query->select(['sum(c.amount) as sum'])->from('p2p_cashback c')->innerJoin(['d' => 'p2p_detail'], 'c.detail_id=d.id')->where(['d.is_deleted' => 0, 'c.is_deleted' => 0, 'd.account_id' => $id])->one();
            $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
            $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
            $cashback = isset($row3['sum']) ? round($row3['sum'], 2) : 0;
            $profit = $balance - ($recharge - $withdraw) + $cashback;
            $profit = round($profit, 2);
            return $this->render('view', [
                'id' => $id,
                'balance' => $balance,
                'recharge' => $recharge,
                'withdraw' => $withdraw,
                'profit' => $profit,
                'cashback' => $cashback,
                'account' => $account,
                'platformName' => $platformName
            ]);
        }
    }

    public function actionCreateDetail() {
        return $this->render('create_detail');
    }

    public function actionCreateDetailAjax() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        if (!isset($data['id']) || !isset($data['type'])) {
            return $this->ajaxResponseError('缺少参数');
        }
        $account = Account::getModelById($data['id']);
        if ($account) {
            $detail = new Detail();
            $detail->platform_id = $account->platform_id;
            $detail->account_id = $account->id;
            $detail->type = $data['type'];
            $detail->amount = $data['amount'];
            $detail->charge = $data['charge'];
            $detail->status = Detail::STATUS_NOT_ARRIVED;
            $detail->current_balance = $data['balance'];
            $detail->time = !empty($data['time']) ? strtotime($data['time']) : 0;
            if ($detail->save()) {
                $account->balance = $data['balance'];
                $account->returned_time = $data['returned'];
                if ($account->save()) {
                    return $this->ajaxResponseSuccess();
                }
            }
            return $this->ajaxResponseError('创建失败');
        }
    }
}