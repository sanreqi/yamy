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
use app\models\ConstData;
use yii\web\Controller;
use app\models\Platform;
use app\models\Account;
use app\controllers\MController;
use yii\db\Query;
use yii\data\Pagination;
use app\models\Detail;
use Yii;

class AccountController extends MController {

    public function actionIndex() {
        $pageSize = 20;
        $data = Account::find()->where(['p2p_account.is_deleted' => 0]);
        //搜索
        $search = [
            'platform_id' => 0,
            'mobile' => 0,
            'balance' => '',
            'keyword' => '',
        ];

        if(isset($_GET['action']) && !empty($_GET['action'])) {
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
                $data->andWhere(['>=', 'balance', (int) $search['balance']]);
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
            $platformName = Platform::getNameById($account['platform_id']);
            $balance = $account['balance'];
            $data = Detail::find()->where(['account_id' => $id, 'is_deleted' => 0]);
            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
            $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
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
            $query = new Query();
            $rows = $query
                ->select(['c.id AS c_id', 'd.id AS d_id', 'platform', 'c.amount AS c_amount',
                    'casher','c.type AS c_type', 'c.status AS c_status', 'c.time AS c_time'])
                ->from('p2p_cashback c')
                ->innerJoin(['d' => 'p2p_detail'], 'c.detail_id=d.id')
                ->where(['d.account_id' => $accountId, 'c.is_deleted' => 0, 'd.is_deleted' => 0])
                ->all();

            return $this->render('cashback', ['models' => $rows]);
        }
    }

    /**
     * 一步到位的创建
     */
    public function actionAbsoluteCreate() {
        $platformOptions = Platform::getOptions();
        $bankAccountOptions = BankAccount::getDisplayOptions();
        $errors = [];
        $post = Yii::$app->request->post('Account');
        if (!empty($post)) {
            //select2批量撸羊毛
            $infoData = $post['info_data'];
            if (empty($infoData)) {
                return;
            }
            $infoData = explode(',', $infoData);
            $count = count($infoData);
            foreach ($infoData as $v) {
                $infoArr = explode('/', $v);
                if (!isset($v[2]) && empty($v[2])) {
                    continue;
                }
                $card = $infoArr[2];
                $infoArr = BankAccount::getByCard($card);
                $model = new Account();
                $model->platform_id = $post['platform_id'];
                $model->bankaccount_id = $infoArr['id'];
                $model->username = $infoArr['username'];
                $model->truename = $infoArr['truename'];
                $model->mobile = $infoArr['reserved_phone'];
                $model->balance = $post['balance'];
                $model->returned_time = !empty($post['returned']) ? strtotime($post['returned']) : 0;
                //保存account
                if ($model->save()) {
                    if (isset($post['isrecharge'])) {
                        $detail = new Detail();
                        //充值
                        $detail->type = Detail::TYPE_RECHARGE;
                        $detail->platform_id = $model->platform_id;
                        $detail->account_id = $model->id;
                        $detail->amount = $post['recharge_amount'];
                        $detail->time = !empty($post['recharge_time']) ? strtotime($post['recharge_time']) : 0;
                        if ($detail->save()) {
                            if (isset($post['iscachback'])) {
                                $cachback = new Cashback();
                                $cachback->detail_id = $detail->id;
                                $cachback->account_id = $model->id;
                                $cachback->platform = Platform::getNameById($model->platform_id);
                                $cachback->amount = $post['cashback_amount'];
                                $cachback->casher = $post['cashback_casher'];
                                $cachback->type = $post['cashback_type'];
                                $cachback->status = $post['cashback_status'];
                                $cachback->time = !empty($post['cashback_time']) ? strtotime($post['cashback_time']) : 0;
                                if ($cachback->save()) {
                                    if ($count == 1) {
                                        $this->redirect(['/account/view', 'id' => $model->id]);
                                        return;
                                    }
                                } else {
                                    //回滚
                                    $model->delete();
                                    $detail->delete();
                                }
                            }
                        } else {
                            $model->delete();
                        }
                    }
                }
            }
            $this->redirect(['/account/index']);
        }
        return $this->render('absolute_create', [
            'platformOptions' => $platformOptions,
            'bankAccountOptions' => $bankAccountOptions,
            'errors' => $errors
        ]);
    }

    public function actionReceived() {
        $today = strtotime(date('Y-m-d'));
        $starttime = $today - 86400 * 3;
        $endtime = $today + 86400 * 4;
        $models = Account::find()->where(['is_deleted' => 0])->andWhere(['between', 'returned_time', $starttime, $endtime])->asArray()->all();
        print_r($models);exit;

    }

}
