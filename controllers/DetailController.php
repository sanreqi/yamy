<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetailController
 *
 * @author Administrator
 */

namespace app\controllers;

use yii\data\Pagination;
use app\models\Platform;
use app\models\Account;
use app\models\Detail;
use app\controllers\MController;
use yii\db\Query;
use Yii;
use yii\filters\AccessControl;

class DetailController extends MController {

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
        $this->checkAccessAndResponse('detail_index');
        $uid = Yii::$app->user->id;
        $data = Detail::find()->where(['is_deleted' => 0, 'uid' => $uid]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);  
        $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $query = new Query();
        $row1 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_RECHARGE, 'uid' => $uid])->one();
        $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_WITHDRAW, 'uid' => $uid])->one();
        $recharge = isset($row1['sum']) ? round($row1['sum'], 2) : 0;
        $withdraw = isset($row2['sum']) ? round($row2['sum'], 2) : 0;
        return $this->render('index', ['models' => $models, 'recharge' => $recharge, 'withdraw' => $withdraw, 'pages' => $pages]);
    }

    public function actionCreate() {
        $uid = Yii::$app->user->id;
        $model = new Detail();
        $errors = [];
        $platOptions = Platform::getOptions();
        $accountOptions = [];
        //是否账户页面创建，默认是明细页面创建
        $byAccount = false;
        $accountId = Yii::$app->request->get('account_id');
        $type = Yii::$app->request->get('type');
        if ($accountId) {
            $account = Account::getAccountById($accountId);
            $this->checkAccessAndResponse('detail_create', ['uid' => $account['uid']]);
            if ($account) {
                $model->account_id = $accountId;
                $model->platform_id = $account['platform_id'];
                $model->type = $type;
                $byAccount = true;
            }
        }

        if (isset($_POST['Detail'])) {
            $post = $_POST['Detail'];
            if (!$byAccount) {
                $model->platform_id = $post['platform_id'];
                $model->account_id = $post['account_id'];
                $model->type = $post['type'];
            }
            $model->amount = $post['amount'];
            $model->charge = $post['charge'];
            $model->uid = $uid;
            $model->time = $post['time'] ? strtotime($post['time']) : 0;
            if ($model->save()) {
                if ($byAccount) {
                    $this->redirect(['/account/view','id'=>$accountId]);
                } else {
                    $this->redirect(['/detail/index']);
                }
            } else {
                $errors = $model->getErrors();
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'byAccount' => $byAccount,
                    'platOptions' => $platOptions,
                    'accountOptions' => $accountOptions,
                    'errors' => $errors
        ]);
    }

    public function actionUpdate() {
        $id = Yii::$app->request->get('id');
        $errors = [];
        if ($id) {
            $model = Detail::findOne(['id' => $id]);
            $this->checkAccessAndResponse('detail_update', ['uid' => $model->uid]);
            $platOptions = Platform::getOptions();
            $accountOptions = Account::getAccountsByPid($model['platform_id']);
            if (isset($_POST['Detail'])) {
                $post = $_POST['Detail'];
                $model->platform_id = $post['platform_id'];
                $model->account_id = $post['account_id'];
                $model->type = $post['type'];
                $model->amount = $post['amount'];
                $model->charge = $post['charge'];
                $model->current_balance = $post['current_balance'];
                $model->time = $post['time'] ? strtotime($post['time']) : 0;
                if ($model->save()) {
                    $this->redirect(['/detail/index']);
                } else {
                    $errors = $model->getErrors();
                }
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'platOptions' => $platOptions,
                    'accountOptions' => $accountOptions,
                    'errors' => $errors
        ]);
    }

    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Detail::findOne(['id' => $id]);
            $this->checkAccessAndResponse('detail_delete', ['uid' => $model->uid]);
            Detail::updateAll(['is_deleted' => 1], 'id=' . $id);
            $this->redirect(['/detail/index']);
        }
    }

    /**
     * 创建明细并修改余额
     */
    public function actionCreateDetail() {
        $accountId = Yii::$app->request->get('account_id');
        $type = Yii::$app->request->get('type');
        $account = Account::findOne(['id' => $accountId]);
        $this->checkAccessAndResponse('detail_create_detail', ['uid' => $account->uid]);
        $model = new Detail();
        if ($accountId && $type) {
            $post = Yii::$app->request->post('Detail');
            if (!empty($post)) {
                //修改账户信息
                $account->balance = $post['balance'];
                $account->returned_time = !empty($post['returned_time']) ? strtotime($post['returned_time']) : 0;
                if ($account->save()) {
                    //新增充值提现记录
                    $model->account_id = $accountId;
                    $model->platform_id = $account->platform_id;
                    $model->type = $type;
                    $model->amount = $post['amount'];
                    $model->charge = $post['charge'];
                    $model->current_balance = $post['balance'];
                    $model->time = !empty($post['time']) ? strtotime($post['time']) : 0;
                    if ($model->save()) {
                        $this->redirect(['/account/view', 'id' => $accountId]);
                    } else {
                        $account->delete();
                    }
                }
            }

            return $this->render('create_detail', [
                'account' => $account,
                'type' => $type
            ]);
        }
        echo 'PAGE NOT EXISTS!';
    }

}
