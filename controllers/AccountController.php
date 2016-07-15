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

use yii\web\Controller;
use app\models\Platform;
use app\models\Account;
use app\controllers\MController;
use yii\db\Query;
use yii\data\Pagination;
use app\models\Detail;
use Yii;

class AccountController extends MController {

    public $enableCsrfValidation = false;

    public function actionIndex() {
        $data = Account::find()->where(['p2p_account.is_deleted' => 0]);
        //搜索
        $search = [
            'platform_id' => 0,
            'mobile' => 0,
            'balance' => '',
            'keyword' => '',
        ];

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

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
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
            Account::updateAll(['is_deleted' => 1], 'id=' . $id);
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
            $row3 = $query->select(['sum(cashback) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'account_id' => $id])->one();
            $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
            $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
            $cashback = isset($row3['sum']) ? round($row3['sum'], 2) : 0;
            $profit = $balance - ($recharge - $withdraw) + $cashback;
            $profit = round($profit, 2);
            return $this->render('view', [
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

}