<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2017/1/1
 * Time: 23:49
 */

namespace app\controllers;

use app\models\BorrowDetail;
use app\models\BorrowPayment;
use app\models\BorrowWay;
use app\models\Detail;
use Yii;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use yii\filters\AccessControl;

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
        return $this->render('detail_index');
    }

    public function actionGetDetailData() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        $data['page'] = isset($data['page']) ? $data['page'] : 0;
        $pageSize = 20;
        $borrowWay = BorrowDetail::find()->where([
            'is_deleted' => 0, 'uid' => Yii::$app->user->id
        ]);
        //默认未还清
        $status = isset($data['status']) ? $data['status'] : 1;
        if ($status) {
            $borrowWay->andWhere(['>', 'remain', 0]);
        } else {
            $borrowWay->andWhere(['remain' => 0]);
        }
        $pages = new Pagination(['totalCount' => $borrowWay->count(), 'pageSize' => $pageSize]);
        //设置第几页
        $pages->setPage($data['page']);
        $pager = LinkPager::widget([
            'pagination' => $pages,
            'firstPageLabel' => '首页',
            'lastPageLabel' => '末页',
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页'
        ]);
        $models = $borrowWay->with('way')->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        if (!empty($models)) {
            foreach ($models as &$model) {
                $model['borrow_time'] = date('Y-m-d', $model['borrow_time']);
                $model['payment_time'] = date('Y-m-d', $model['payment_time']);
            }
        }
        return $this->ajaxResponseSuccess([
            'data' => $models,
            'pager' => $pager,
            'page' => $pages->page,
            'count' => count($models),
        ]);
    }

    public function actionDetailCreate() {
        $model = new BorrowDetail();
        $wayOptions = BorrowWay::getWayOptions();
        $post = Yii::$app->request->post();
        $errors = [];
        if ($model->load($post)) {
            $model->uid = Yii::$app->user->id;
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
        $id = Yii::$app->request->get("id");
        $model = BorrowDetail::getDetailById($id);
        if (!empty($model)) {
            $wayOptions = BorrowWay::getWayOptions();
            $post = Yii::$app->request->post();
            $errors = [];
            if ($model->load($post)) {
                $model->uid = Yii::$app->user->id;
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
        echo 'PAGE NOT EXISTS!';
        exit;
    }
    public function actionDetailDelete() {
        $id = Yii::$app->request->get("id");
        BorrowDetail::updateAll(['is_deleted' => 1], ['id' => $id]);
        BorrowPayment::updateAll(['is_deleted' => 1], ['detail_id' => $id]);
        return $this->ajaxResponseSuccess();
    }
    /****************借款记录增删改查********************/

    /****************还款记录增删改查********************/
    public function actionPaymentIndex() {
        $id = Yii::$app->request->get("id");
        $detail = BorrowDetail::getDetailById($id);
        $models = BorrowPayment::getPaymentsByDetailId($id);
        return $this->render('payment_index', [
            'detail' => $detail,
            'models' => $models,
        ]);
    }
    public function actionPaymentCreate() {
        $detailId = Yii::$app->request->get('id');
        $detail = BorrowDetail::getDetailById($detailId);
        $errors = [];
        if (!empty($detail)) {
            $post = Yii::$app->request->post();
            $model = new BorrowPayment();
            if ($model->load($post)) {
                $model->detail_id = $detailId;
                $model->time = strtotime($model['time']);
                $model->uid = Yii::$app->user->id;
                if ($model->save()) {
                    $detail->remain = $model->remain;
                    $detail->save();
                    return $this->redirect(['/borrow/payment-index', 'id' => $detailId]);
                } else {
                    $errors = $model->getErrors();
                }
            }
            return $this->render('payment_form', [
                'model' => $model,
                'detail' => $detail,
                'errors' => $errors,
            ]);
        }
        echo 'PAGE NOT EXISTS';
        exit;
    }
    public function actionPaymentUpdate() {
        $detailId = Yii::$app->request->get('detail_id');
        $id = Yii::$app->request->get('id');
        $detail = BorrowDetail::getDetailById($detailId);
        $model = BorrowPayment::findModelById($id);
        $errors = [];
        if (!empty($model) && !empty($detail)) {
            $post = Yii::$app->request->post();
            if ($model->load($post)) {
                $model->detail_id = $detailId;
                $model->time = strtotime($model['time']);
                if ($model->save()) {
                    $detail->save();
                    return $this->redirect(['/borrow/payment-index', 'id' => $detailId]);
                } else {
                    $errors = $model->getErrors();
                }
            }
            return $this->render('payment_form', [
                'model' => $model,
                'detail' => $detail,
                'errors' => $errors,
            ]);
        }
        echo 'PAGE NOT EXISTS';
        exit;
    }
    public function actionPaymentDelete() {
        $id = Yii::$app->request->get("id");
        $model = BorrowPayment::findModelById($id);
        if (!empty($model)) {
            $model->is_deleted = 1;
            if ($model->save()) {
                return $this->ajaxResponseSuccess();
            }
        }
        return $this->ajaxResponseError("删除失败");
    }
    /****************还款记录增删改查********************/


}