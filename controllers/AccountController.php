<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountController
 *
 * @author Administrator
 */

namespace app\controllers;

use app\models\BankAccount;
use app\models\Cashback;
use app\models\form\AccountForm;
use yii\filters\AccessControl;
use app\models\Platform;
use app\models\Account;
use yii\db\Query;
use yii\data\Pagination;
use app\models\Detail;
use Yii;

class AccountController extends MController {

    /**
     * 登录才能访问
     * @return array
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->checkAccessAndResponse('account_index');
        $pageSize = 20;
        $data = Account::find()->where(['p2p_account.is_deleted' => 0, 'p2p_account.uid' => Yii::$app->user->id]);
        //搜索
        $search = [
            'platform_id' => 0,
            'mobile' => 0,
            'balance' => '',
            'keyword' => '',
        ];

        if (isset($_GET['action']) && !empty($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'received') {
                //最近回款，今天+前3天+后3天共7天
                $today = strtotime(date('Y-m-d'));
                $starttime = $today - 86400 * 3;
                $endtime = $today + 86400 * 4;
                $data->andWhere(['between', 'returned_time', $starttime, $endtime]);
            } elseif ($action == 'high_profit') {
                //一页就够了
                $pageSize = 3000;
            }
    }
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $search['keyword'] = $_GET['keyword'];
            $keyword = trim($search['keyword']);

            if (strpos($keyword, ' ') === false) {
                if (is_numeric($keyword)) {
                    $data->andWhere(['like', 'mobile', $keyword]);
                } else {
                    $data->innerJoinWith('platform p')->andWhere(['like', 'p.name', $keyword]);
                }
            } else {
                $word = explode(' ', $keyword);
                $data->innerJoinWith('platform p')->andWhere(['and', ['like', 'p.name', $word[0]], ['like', 'mobile', $word[1]]]);
            }
        } elseif (isset($_GET['Search']) && !empty($_GET['Search'])) {
            //非关键字搜索
            $SearchParams = $_GET['Search'];
            if (isset($SearchParams['platform_id']) && !empty($SearchParams['platform_id'])) {
                $search['platform_id'] = $SearchParams['platform_id'];
                $data->andWhere(['platform_id' => $search['platform_id']]);
            }
            if (isset($SearchParams['mobile']) && !empty($SearchParams['mobile'])) {
                $search['mobile'] = $SearchParams['mobile'];
                $data->andWhere(['mobile' => $search['mobile']]);
            }
            if (isset($SearchParams['balance']) && !empty($SearchParams['balance'])) {
                $search['balance'] = $SearchParams['balance'];
                $data->andWhere(['>=', 'balance', (int)$search['balance']]);
            }
        }
        if (isset($_GET['order_returned'])) {
            $order = $_GET['order_returned'];
            $data->orderBy('returned_time ' . $order);
        }

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize]);
        $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $query = new Query();
        $row = $query->select(['sum(balance) as sum'])->from('p2p_account')->where(['is_deleted' => 0])->one();
        $sum = isset($row['sum']) ? round($row['sum'], 2) : 0;
        $platOptions = Platform::getOptions();
        $mobileOptions = Account::getMobileOptions();

        return $this->render('index', [
            'models' => $models,
            'sum' => $sum,
            'pages' => $pages,
            'search' => $search,
            'platOptions' => $platOptions,
            'mobileOptions' => $mobileOptions
        ]);
    }

    public function actionCreate() {
        $this->checkAccessAndResponse('account_create');
        $model = new Account();
        $errors = [];
        $options = Platform::getOptions();
        if (isset($_POST['Account'])) {
            $post = $_POST['Account'];
            $model->platform_id = $post['platform_id'];
            $model->username = $post['username'];
            $model->mobile = $post['mobile'];
            $model->balance = $post['balance'];
            $model->returned_time = strtotime($post['returned']);
            $model->uid = Yii::$app->user->id;
            if ($model->save()) {
                $this->redirect(['/account/index']);
            } else {
                $errors = $model->getErrors();
            }
        }
        return $this->render('create', ['model' => $model, 'options' => $options, 'errors' => $errors]);
    }

    public function actionUpdate() {
        $errors = [];
        $options = Platform::getOptions();
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Account::findOne(['id' => $id]);
            //权限检查，只能操作自己的账户
            $this->checkAccessAndResponse('account_update', ['uid' => $model->uid]);
            if (!empty($model)) {
                if (isset($_POST['Account'])) {
                    $post = $_POST['Account'];
                    $model->platform_id = $post['platform_id'];
                    $model->username = $post['username'];
                    $model->mobile = $post['mobile'];
                    $model->balance = $post['balance'];
                    $model->returned_time = strtotime($post['returned']);
                    if ($model->save()) {
                        $this->redirect(['/account/index']);
                    } else {
                        $errors = $model->getErrors();
                    }
                }
            }
        }
        return $this->render('update', ['model' => $model, 'options' => $options, 'errors' => $errors]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Account::findOne(['id' => $id]);
            //权限检查，只能操作自己的账户
            $this->checkAccessAndResponse('account_delete', ['uid' => $model->uid]);
            //删除账号并且删除相关返现和明细
            Account::updateAll(['is_deleted' => 1], 'id=' . $id);
            Detail::updateAll(['is_deleted' => 1], 'account_id=' . $id);
            Cashback::updateAll(['is_deleted' => 1], 'account_id=' . $id);
            $this->redirect(['/account/index']);
        }
    }

    public function actionView() {
        $id = Yii::$app->request->get('id', 0);
        $account = Account::getAccountById($id);
        if ($account) {
            //验证
            $this->checkAccessAndResponse('account_view', ['uid' => $account['uid']]);
            $uid = Yii::$app->user->id;
            $platformName = Platform::getNameById($account['platform_id']);
            $balance = $account['balance'];
            $data = Detail::find()->where(['account_id' => $id, 'is_deleted' => 0, 'uid' => $uid]);
            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
            $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
            $query = new Query();
            $row1 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_RECHARGE, 'uid' => $uid])->one();
            $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id, 'type' => Detail::TYPE_WITHDRAW, 'uid' => $uid])->one();
            $row3 = $query->select(['sum(c.amount) as sum'])->from('p2p_cashback c')->innerJoin(['d' => 'p2p_detail'], 'c.detail_id=d.id')->where(['d.is_deleted' => 0, 'c.is_deleted' => 0, 'd.account_id' => $id, 'c.uid' => $uid])->one();
            $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
            $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
            $cashback = isset($row3['sum']) ? round($row3['sum'], 2) : 0;
            $profit = $balance - ($recharge - $withdraw) + $cashback;
            $profit = round($profit, 2);
            return $this->render('view', [
                'id' => $id,
                'models' => $models,
                'balance' => $balance,
                'pages' => $pages,
                'recharge' => $recharge,
                'withdraw' => $withdraw,
                'profit' => $profit,
                'cashback' => $cashback,
                'account' => $account,
                'platformName' => $platformName
            ]);
        }
    }

    public function actionCashback() {
        $accountId = Yii::$app->request->get('account_id');
        if ($accountId) {
            $account = Account::getAccountById($accountId);
            //验证
            $this->checkAccessAndResponse('account_cashback', ['uid' => $account['uid']]);
            $uid = Yii::$app->user->id;
            $query = new Query();
            $rows = $query
                ->select(['c.id AS c_id', 'd.id AS d_id', 'platform', 'c.amount AS c_amount',
                    'casher', 'c.type AS c_type', 'c.status AS c_status', 'c.time AS c_time'])
                ->from('p2p_cashback c')
                ->innerJoin(['d' => 'p2p_detail'], 'c.detail_id=d.id')
                ->where(['d.account_id' => $accountId, 'c.is_deleted' => 0, 'd.is_deleted' => 0,
                        'c.uid' => $uid, 'd.uid' => $uid])
                ->all();

            return $this->render('cashback', ['models' => $rows]);
        }
    }

    /**
     * 批量创建
     */
    public function actionBatchCreate() {
        $this->checkAccessAndResponse('account_batch_create');
        $uid = Yii::$app->user->id;
        $form = new AccountForm();
        $platformOptions = Platform::getOptions();
        $bankAccountOptions = BankAccount::getDisplayOptions();
        $bankIdsArray = [];
        $errors = [];
        $validate = true;
        $post = Yii::$app->request->post('Account');
        if (!empty($post)) {
            //填充表单数据
            $form->platformId = $post['platform_id'];
            $form->bankAccountIds = $post['bankaccount_ids'];
            $form->balance = $post['balance'];
            $form->returnedTime = $post['returned'];
            $form->isRecharge = isset($post['isrecharge']) ? 1 : 0;
            $form->rechargeAmount = $post['recharge_amount'];
            $form->rechargeTime = $post['recharge_time'];
            $form->isCashback = isset($post['iscashback']) ? 1 : 0;
            $form->casher = $post['cashback_casher'];
            $form->cashbackAmount = $post['cashback_amount'];
            $form->cashbackType = $post['cashback_type'];
            $form->cashbackStatus = $post['cashback_status'];
            $form->cashbackTime = $post['cashback_time'];
            $bankIdsArray = explode(',', $form->bankAccountIds);
            //全部验证
            foreach ($bankIdsArray as $bankId) {
                $bankAccount = BankAccount::getModelById($bankId);
                $form->registeredMobile = $bankAccount['reserved_phone'];
                if (!$form->validate()) {
                    $validate = false;
                    $errors = $form->getErrors();
                    break;
                }
            }
            if ($validate) {
                //验证通过
                foreach ($bankIdsArray as $bankId) {
                    $bankAccount = BankAccount::getModelById($bankId);
                    if (!empty($bankAccount)) {
                        $p2pAccount = new Account();
                        $p2pAccount->platform_id = $form->platformId;
                        $p2pAccount->bankaccount_id = $bankId;
                        $p2pAccount->mobile = $bankAccount['reserved_phone'];
                        $p2pAccount->truename = $bankAccount['truename'];
                        $p2pAccount->returned_time = strtotime($form->returnedTime);
                        $p2pAccount->balance = $form->balance;
                        $p2pAccount->uid = $uid;
                        $p2pAccount->is_deleted = 0;
                        $p2pAccount->save();

                        if ($form->isRecharge) {
                            $detail = new Detail();
                            //充值
                            $detail->type = Detail::TYPE_RECHARGE;
                            $detail->platform_id = $form->platformId;
                            $detail->account_id = $p2pAccount->id;
                            $detail->amount = $form->rechargeAmount;
                            $detail->current_balance = $p2pAccount->balance;
                            $detail->uid = $uid;
                            $detail->time = !empty($form->rechargeTime) ? strtotime($form->rechargeTime) : 0;
                            $detail->save();
                            //充值若未勾选则返现无效
                            if ($form->isCashback) {
                                //返现
                                $cachback = new Cashback();
                                $cachback->detail_id = $detail->id;
                                $cachback->account_id = $p2pAccount->id;
                                $cachback->platform = Platform::getNameById($p2pAccount->platform_id);
                                $cachback->amount = $form->cashbackAmount;
                                $cachback->casher = $form->casher;
                                $cachback->type = $form->cashbackType;
                                $cachback->status = $form->cashbackStatus;
                                $cachback->uid = $uid;
                                $cachback->time = !empty($form->cashbackTime) ? strtotime($form->cashbackTime) : 0;
                                $cachback->save();
                            }
                        }
                    }
                }
                //单个账号创建跳转到详情页，多个跳转到列表页
                if (count($bankIdsArray) == 1) {
                    $this->redirect(['/account/view', 'id' => $p2pAccount->id]);
                } else {
                    $this->redirect(['/account/index']);
                }
            }
        }

        return $this->render('absolute_create', [
            'form' => $form,
            'platformOptions' => $platformOptions,
            'bankAccountOptions' => $bankAccountOptions,
            'bankIdsArray' => $bankIdsArray,
            'errors' => $errors
        ]);
    }

    /*************之后都没用****************/
    public function actionChangeReturnedAjax() {
        $post = Yii::$app->request->post();
        $originStamp = strtotime($post['date']);
        $newStamp = $originStamp + $post['days'] * 86400;
        $newDate = date('Y-m-d', $newStamp);
        return json_encode(['date' => $newDate]);
    }

    public function actionReceived() {
        $today = strtotime(date('Y-m-d'));
        $starttime = $today - 86400 * 3;
        $endtime = $today + 86400 * 4;
        $models = Account::find()->where(['is_deleted' => 0])->andWhere(['between', 'returned_time', $starttime, $endtime])->asArray()->all();
        print_r($models);
        exit;

    }

    public function actionTest() {
        $form = new AccountForm();
        $form->platformId = 1;
        $form->registeredSimId = 1;
        $form->bankAccountIds = [1];
        $form->isRecharge = 1;
        if ($form->validate()) {
            echo 'ok';
        } else {
            $err = $form->getErrors();
            print_r($err);
            exit;
        }
    }

}
