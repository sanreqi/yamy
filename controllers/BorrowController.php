<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2017/1/1
 * Time: 23:49
 */

namespace app\controllers;

use app\models\BorrowDetail;
use app\models\BorrowWay;
use Yii;
use app\controllers\MController;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * 借款控制器,用于计算利息
 * Class BorrowController
 * @package app\controllers
 */
class BorrowController extends MController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'login', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /****************借款渠道增删改查********************/
    public function actionWayIndex() {
        $models = BorrowWay::find()->where([
            'is_deleted' => 0, 'uid' => Yii::$app->user->id
        ])->asArray()->all();
        return $this->render('way_index', ['models' => $models]);
    }

    public function actionWayCreate() {
        $model = new BorrowWay();
        $post = Yii::$app->request->post();
        $errors = [];
        if ($model->load($post)) {
            $model->uid = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['/borrow/way-index']);
            } else {
                $errors = $model->getErrors();
            }
        }
        return $this->render('way_form', [
            'model' => $model,
            'errors' => $errors
        ]);
    }

    public function actionWayUpdate() {
        $id = Yii::$app->request->get("id");
        $model = BorrowWay::findModelById($id);
        if (!empty($model)) {
            $post = Yii::$app->request->post();
            $errors = [];
            if ($model->load($post)) {
                if ($model->save()) {
                    return $this->redirect(['/borrow/way-index']);
                } else {
                    $errors = $model->getErrors();
                }
            }
            return $this->render('way_form', [
                'model' => $model,
                'errors' => $errors
            ]);
        }
    }

    public function actionWayDelete() {
        $id = Yii::$app->request->get("id");
        $model = BorrowWay::findModelById($id);
        if (!empty($model)) {
            $model->is_deleted = 1;
            if ($model->save()) {
                return $this->ajaxResponseSuccess();
            }
        }
        return $this->ajaxResponseError("删除失败");
    }
    /****************借款渠道增删改查********************/

    /****************借款记录增删改查********************/
    public function actionDetailIndex() {
        $status = Yii::$app->request->get("status") ? 1 : 0;
        if ($status) {
            //未还清
            $where = ['>', 'remain', 0];
        } else {
            //已还清
            $where = ['remain' => 0];
        }
        $models = BorrowWay::find()->where([
            'is_deleted' => 0, 'uid' => Yii::$app->user->id
        ])->andWhere($where)->asArray()->all();
        return $this->render('detail_index', ['models' => $models]);
    }
    public function actionDetailCreate() {
        $model = new BorrowDetail();
        $wayOptions = BorrowWay::getWayOptions();
        $post = Yii::$app->request->post();
        $errors = [];
        if ($model->load($post)) {
            $model->uid = Yii::$app->user->id;
            $model->remain = $model->amount;
            $model->borrow_time = !empty($model->borrow_time) ? strtotime($model->borrow_time) : 0;
            $model->payment_time = !empty($model->payment_time) ? strtotime($model->payment_time) : 0;
            if ($model->save()) {
                return $this->redirect(['/borrow/detail-index']);
            } else {
                $errors = $model->getErrors();
            }
        }
        return $this->render('detail_form', [
            'model' => $model,
            'wayOptions' => $wayOptions,
            'errors' => $errors,
        ]);
    }
    public function actionDetailUpdate() {

    }
    public function actionDetailDelete() {

    }
    /****************借款记录增删改查********************/

    /****************还款记录增删改查********************/
    public function actionPaymentIndex() {

    }
    public function actionPaymentCreate() {

    }
    public function actionPaymentUpdate() {

    }
    public function actionPaymentDelete() {

    }
    /****************还款记录增删改查********************/


}