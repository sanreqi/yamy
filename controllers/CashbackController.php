<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashbackController
 *
 * @author Administrator
 */

namespace app\controllers;

use yii\web\Controller;
use app\models\Platform;
use app\models\Account;
use app\models\Cashback;
use app\models\Detail;
use app\controllers\MController;
use yii\db\Query;
use yii\data\Pagination;
use Yii;

class CashbackController extends MController {
    
    public function actionIndex() {
        $data = Cashback::find()->where(['is_deleted' => 0]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);  
        $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $query = new Query();
        $row = $query->select(['sum(amount) as sum'])->from('p2p_cashback')->where(['is_deleted' => 0])->one();
        $cashback = isset($row['sum']) ? round($row['sum'], 2) : 0;
        return $this->render('index', ['models' => $models, 'cashback' => $cashback, 'pages' => $pages]);
    }
    
    public function actionCreate() {
        $model = new Cashback();
        $id = Yii::$app->request->get('detail_id');
        if ($id) {    
            $detail = Detail::findOne(['id' => $id]);
            if (isset($_POST['Cashback'])) {
                $post = $_POST['Cashback'];
                $model->detail_id = $id;
                $model->platform = Platform::getNameById($detail['platform_id']);
                $model->amount = $post['amount'];
                $model->casher = $post['casher'];
                $model->type = $post['type'];
                $model->status = $post['status'];
                $model->time = $post['time'] ? strtotime($post['time']) : 0; 
                if ($model->save()) {
                    Detail::updateAll(['cashback' => $model->amount], 'id='.$id);
                    $this->redirect(['/cashback/index']);
                }
            }
            return $this->render('create', ['model' => $model, 'detailId' => $id]);
        }
    }
    
    public function actionUpdate() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Cashback::findOne(['id' => $id]);
            if (isset($_POST['Cashback'])) {
                $post = $_POST['Cashback'];       
                $model->amount = $post['amount'];
                $model->casher = $post['casher'];
                $model->type = $post['type'];
                $model->status = $post['status'];
                $model->time = $post['time'] ? strtotime($post['time']) : 0;
                if ($model->save()) {
                    Detail::updateAll(['cashback' => $model->amount], 'id=' . $model['detail_id']);
                    $this->redirect(['/cashback/index']);
                }
            }
            
            return $this->render('update', ['model' => $model, 'detailId' => $model['detail_id']]);
        }
    }
    
    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Cashback::findOne(['id' => $id]);
            Detail::updateAll(['cashback' => 0], 'id=' . $model['detail_id']);
            Cashback::updateAll(['is_deleted' => 1], 'id=' . $id);
            $this->redirect(['/cashback/index']);
        }
    }
    
    
}
