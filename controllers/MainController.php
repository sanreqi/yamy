<?php
/**
 * User: srq
 * Date: 2016/11/23
 * Time: 9:29
 */

namespace app\controllers;

use app\models\Account;
use app\models\Detail;
use app\models\Remark;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;

class MainController extends MController {

    private $accountTable = 'p2p_account';
    private $platformTable = 'p2p_platform';
    /*默认action*/
    public $defaultAction = 'index';

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
        $this->checkAccessAndResponse('main_index');
        $uid = Yii::$app->user->id;
        $accounts = Account::find()
            ->where(['is_deleted' => 0, 'uid' => $uid])
            ->andWhere(['>', 'balance', 1])
            ->andWhere(['!=', 'returned_time', 0])
            ->asArray()
            ->all();
        $remarks = Remark::find()->where(['is_deleted' => 0, 'uid' => $uid])->asArray()->all();
        return $this->render('index', [
            'accounts' => $accounts,
            'remarks' => $remarks
        ]);
    }

    /**
     * 添加备注操作
     * @return string
     */
    public function actionCreateRemark() {
        $this->checkAccessAndResponse('main_remark_create');
        $post = Yii::$app->request->post();
        $dates = explode('/', $post['date']);
        $stamp = strtotime($dates[2] . '-' . $dates[1] . '-' . $dates[0]);
        $model = new Remark();
        $model->content = $post['content'];
        $model->time = $stamp;
        $model->createtime = time();
        $model->uid = Yii::$app->user->id;
        $model->is_deleted = 0;
        if ($model->save()) {
            return json_encode(["status" => 1, 'data_id' => $model->id]);
        } else {
            return json_encode(["status" => 0]);
        }
    }

    /**
     * 删除备注
     * @return string
     */
    public function actionDeleteRemark() {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $remark = Remark::getModelById($id);
        $this->checkAccessAndResponse('main_remark_delete', ['uid' => $remark->uid]);
        $model = new Remark();
        $model->deleteAll(['id' => $id]);
        return json_encode(["status" => 1]);
    }

    /**
     * 获取各个平台投资金额
     */
    public function actionGetPlatformAmountAjax() {
        $this->checkAccessAndResponse('main_get_platform_amount');
        $query = new Query();
        $result = $query
            ->select(['SUM(a.balance) AS value', 'p.name'])
            ->from($this->accountTable . ' a')
            ->innerJoin(['p' => $this->platformTable], 'a.platform_id=p.id')
            ->where(['a.is_deleted' => 0, 'p.is_deleted' => 0, 'p.landmine' => 0, 'a.uid' => Yii::$app->user->id])
            ->groupBy('a.platform_id')
            ->having(['>', 'value', 0])
            ->all();
        return json_encode(['data' => $result]);
    }

    public function actionGetProfitAjax() {
        $this->checkAccessAndResponse('main_get_profit');
        $total = 0;
        $profits = [];
        //2016年6月-11月
        $months2016 = [6, 7, 8, 9, 10, 11, 12];
        foreach ($months2016 as $v) {
            $startTime = strtotime('2016-' . $v . '-1');
            if ($v == 12) {
                $endTime = strtotime('2017-1-1') - 1;
            } else {
                $endDate = '2016-' . ($v + 1) . '-1';
                $endTime = strtotime($endDate) - 1;
            }
            $r = Detail::getProfitsByPeriod($startTime, $endTime);
            $profits[] = $r;
            $total += $r;
        }
        $month2017 = [1, 2, 3, 4, 5, 6];
        foreach ($month2017 as $v) {
            $startTime = strtotime('2017-' . $v . '-1');
            if ($v == 12) {
                $endTime = strtotime('2017-1-1') - 1;
            } else {
                $endDate = '2017-' . ($v + 1) . '-1';
                $endTime = strtotime($endDate) - 1;
            }
            $r = Detail::getProfitsByPeriod($startTime, $endTime);
            $profits[] = $r;
            $total += $r;
        }
        return json_encode(['status' => 1, 'months' => [6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6], 'profits' => $profits, 'total' => $total]);
    }

    //    public function actionTest() {
    //        $s = strtotime('2016-12-1');
    //        $e = strtotime('2017-1-1') - 1;
    //        $r = Detail::getProfitsByPeriod($s, $e);
    //        echo $r; exit;
    //    }

}