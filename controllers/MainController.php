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

class MainController extends MController {

    private $accountTable = 'p2p_account';
    private $platformTable = 'p2p_platform';

    public function actionIndex() {
        $accounts = Account::find()
            ->where(['is_deleted' => 0])
            ->andWhere(['>', 'balance', 0])
            ->andWhere(['!=', 'returned_time', 0])
            ->asArray()
            ->all();
        $remarks = Remark::find()->where(['is_deleted' => 0])->asArray()->all();
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
        $post = Yii::$app->request->post();
        $dates = explode('/', $post['date']);
        $stamp = strtotime($dates[2] . '-' . $dates[1] . '-' . $dates[0]);
        $model = new Remark();
        $model->content = $post['content'];
        $model->time = $stamp;
        $model->createtime = time();
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
        $model = new Remark();
        $model->deleteAll(['id' => $id]);
        return json_encode(["status" => 1]);
    }

    /**
     * 获取各个平台投资金额
     */
    public function actionGetPlatformAmountAjax() {
        $query = new Query();
        $result = $query
            ->select(['SUM(a.balance) AS value', 'p.name'])
            ->from($this->accountTable . ' a')
            ->innerJoin(['p' => $this->platformTable], 'a.platform_id=p.id')
            ->where(['a.is_deleted' => 0, 'p.is_deleted' => 0, 'p.landmine' => 0])
            ->groupBy('a.platform_id')
            ->having(['>', 'value', 0])
            ->all();
        return json_encode(['data' => $result]);
    }

    public function actionGetProfitAjax() {
        $profits = [];
        //2016年6月-11月
        $months = [6, 7, 8, 9, 10, 11, 12];
        foreach ($months as $v) {
            $startTime = strtotime('2016-' . $v . '-1');
            if ($v == 12) {
                $endTime = strtotime('2017-1-1') - 1;
            } else {
                $endDate = '2016-' . ($v + 1) . '-1';
                $endTime = strtotime($endDate) - 1;
            }
            $r = Detail::getProfitsByPeriod($startTime, $endTime);
            $profits[] = $r;
        }
//        $startTime = strtotime('2016-8-1');
//        $endTime = strtotime('2016-9-1');
//        $r = Detail::getProfitsByPeriod($startTime, $endTime);
        return json_encode(['status' => 1, 'months' => $months, 'profits' => $profits]);
    }

    public function actionTest() {
        $s = strtotime('2016-10-1');
        $e = strtotime('2016-11-1') - 1;
        $r = Detail::getProfitsByPeriod($s, $e);
        echo $r; exit;

        $sql = '
          SELECT SUM(current_balance) AS sum FROM p2p_detail WHERE id in (
            SELECT MAX(id) FROM(
              SELECT * FROM (
                SELECT b.id,b.account_id,a.max_id FROM (
                  SELECT MAX(id) AS max_id, account_id from p2p_detail WHERE type=2 AND is_deleted=0 AND time BETWEEN 0 AND 100 GROUP BY account_id
                  ) a INNER JOIN p2p_detail b ON a.account_id=b.account_id
                ) c WHERE c.id<c.max_id
            ) d GROUP BY d.account_id
          )
        ';
        $s = strtotime('2016-10-1');
        $e = strtotime('2016-11-1') - 1;
        $r = Detail::getProfitsByPeriod($s, $e);
        echo $r;
        $sql = '(SELECT MAX(id) AS max_id, account_id from p2p_detail WHERE is_deleted=0 GROUP BY account_id)';
    }

}