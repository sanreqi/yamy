<?php

namespace app\models\form;
use app\models\Account;

/**
 * User: srq
 * Date: 2016/11/21
 * Time: 9:23
 */
class AccountForm extends \yii\base\Model {
    /*表单字段*/
    public $platformId;
    //单个字段时候添加场景
    public $registeredSimId;
    public $registeredMobile;
    public $bankAccountIds;
    public $balance;
    public $returnedTime;
    public $isRecharge;
    public $rechargeAmount;
    public $rechargeTime;
    public $isCashback;
    public $casher;
    public $cashbackAmount;
    public $cashbackType;
    public $cashbackStatus;
    public $cashbackTime;
    public $days;

    /*
     * 表单验证规则
     * 若是否充值勾选，充值金额必填
     * 若是否返现勾选，返现人返现金额必填
     * 平台id和注册手机号联合唯一性
     */
    public function rules() {
        return [
            [['platformId', 'bankAccountIds'], 'required'],
            [['balance'], 'integer'],
            [['rechargeAmount'], 'integer', 'when' => function ($model) {
                return $model->isRecharge == 1;
            }],
            [['rechargeAmount'], 'required', 'when' => function ($model) {
                return $model->isRecharge == 1;
            }],
            [['casher', 'cashbackAmount'], 'required', 'when' => function ($model) {
                return $model->isCashback == 1 && $model->isRecharge == 1;
            }],
            [['cashbackAmount'], 'integer', 'when' => function ($model) {
                return $model->isCashback == 1 && $model->isRecharge == 1;
            }],
            ['registeredMobile', 'validateMobileUnique'],
        ];
    }

    public function validateMobileUnique() {
        $account = Account::find()->where(['platform_id' => $this->platformId, 'mobile' => $this->registeredMobile])->one();
        if (!empty($account)) {
            $this->addError('registeredMobile', 'The mbile ' . $this->registeredMobile . ' has registered.');
        }
        return true;
    }

    public function init() {
        parent::init();
        //默认31天后
        $this->returnedTime = date('Y-m-d', strtotime(date('Y-m-d')) + 31 * 86400);
        $this->isRecharge = 1;
        $this->rechargeTime = date('Y-m-d');
        $this->isCashback = 1;
        $this->casher = '比蓝更蓝';
        $this->cashbackTime = date('Y-m-d');
        $this->bankAccountIds = '';
    }

}