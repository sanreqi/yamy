<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2017/1/1
 * Time: 23:49
 */

namespace app\controllers;

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
//        $this->checkAccessAndResponse('permission_index');
        $dataProvider = new ActiveDataProvider([
            'query' => BorrowWay::find()->where(['is_deleted' => 0, 'uid' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        return $this->render('way_index', ['dataProvider' => $dataProvider]);
    }

    public function actionWayCreate() {
        $model = new BorrowWay();
        return $this->render('way_form', ['model' => $model]);
    }

    public function actionWayUpdate() {

    }

    public function actionWayDelete() {

    }
    /****************借款渠道增删改查********************/


}