<?php
/**
 * User: srq
 * Date: 2016/8/27
 * Time: 13:31
 */

namespace app\controllers;

use app\models\BankAccount;
use yii\data\Pagination;
use Yii;

class BankController extends MController {

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

    /**
     * 列表页面
     * @return string
     */
    public function actionIndex() {
        $this->checkAccessAndResponse('bank_index');
        $data = BankAccount::find()->where(['is_deleted' => 0, 'uid' => Yii::$app->user->id]);
        //分页类
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
        $models = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', ['models' => $models, 'pages' => $pages]);
    }

    /**
     * 创建个人信息账号(以银行卡位单位元)
     * @return string
     */
    public function actionCreate() {
        $this->checkAccessAndResponse('bank_create');
        $post = Yii::$app->request->post();
        if (!empty($post['BankAccount'])) {;
            $model = new BankAccount();
            $model->load($post);
            $model->uid = Yii::$app->user->id;
            if ($model->save()) {
                $this->redirect(['/bank/index']);
            }
        }

        return $this->render('create');
    }

    /**
     * 修改个人账号
     * @return string
     */
    public function actionUpdate() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = BankAccount::findOne(['id' => $id, 'is_deleted' => 0]);
            $this->checkAccessAndResponse('bank_update', ['uid' => $model->uid]);
            $post = Yii::$app->request->post();
            if (!empty($post['BankAccount'])) {
                $model->load($post);
                if ($model->save()) {
                    $this->redirect(['/bank/index']);
                }
            }
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $model = BankAccount::findOne(['id' => $id, 'is_deleted' => 0]);
            $this->checkAccessAndResponse('bank_update', ['uid' => $model->uid]);
            BankAccount::updateAll(['is_deleted' => 1], 'id=' . $id);
            $this->redirect(['/bank/index']);
        }
    }

}