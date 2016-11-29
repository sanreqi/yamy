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
use app\models\form\AccountForm;
use app\models\Platform;

class AccountController extends TController {

    public function actionIndex() {
        return $this->render('index');
    }

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
    public function actionSaveBatchCreate() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        $platformId = $data['platform_id'];
        $bankAccountId = $data['bank_account_id'];
        $bankAccount = BankAccount::getModelById($bankAccountId);
        if (!empty($bankAccount)) {
            //验证账号是否已存在
            $mobile = $bankAccount['reserved_phone'];
//            $account = Account::find()->where(['platform_id' => $platformId, 'mobile' => $mobile])->one();
//            if (!empty($account)) {
//                return $this->ajaxResponseError("账号已存在");
//            }

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
            }
            return $this->ajaxResponseSuccess();
        }
    }

}