<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlatformController
 *
 * @author Administrator
 */

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\Platform;
use app\models\Account;
use app\controllers\MController;
use yii\data\Pagination;
use Yii;

class PlatformController extends MController {

    public $enableCsrfValidation = false;
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->checkAccessAndResponse('platform_index');
        $data = Platform::find()->where(['is_deleted' => 0]);
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $data->andWhere(['like', 'name', $_GET['keyword']]);
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);  
        $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', ['models' => $models, 'pages' => $pages]);
    }

    public function actionCreate() {
        $this->checkAccessAndResponse('platform_create');
        $model = new Platform();
        $errors = [];
        if (isset($_POST['Platform'])) {
            $post = $_POST['Platform'];
            $model->name = $post['name'];
            $model->location = $post['location'];
            if ($model->save()) {
                $this->redirect(['/platform/index']);
            } else {
                $errors = $model->getErrors();
            }
        }
        return $this->render('create', ['model' => $model, 'errors' => $errors]);
    }

    public function actionUpdate() {
        $id = Yii::$app->request->get('id');
        $errors = [];
        if ($id) {
            $model = Platform::findOne(['id' => $id]);
            $this->checkAccessAndResponse('platform_update', ['uid' => $model->uid]);
            if (isset($_POST['Platform'])) {
                $post = $_POST['Platform'];
                $model->name = $post['name'];
                $model->location = $post['location'];
                if ($model->save()) {
                    $this->redirect(['/platform/index']);
                } else {
                    $errors = $model->getErrors();
                }
            }
            return $this->render('update', ['model' => $model, 'errors' => $errors]);
        }
    }

    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = Platform::findOne(['id' => $id]);
            $this->checkAccessAndResponse('platform_delete', ['uid' => $model->uid]);
            Platform::updateAll(['is_deleted' => 1], 'id=' . $id);
            $this->redirect(['/platform/index']);
        }
    }
    
    public function actionGetAccountsAjax() {
        $id = Yii::$app->request->get('id');
        $accounts = Account::getAccountsByPid($id);
        return json_encode($accounts);
    }

}
