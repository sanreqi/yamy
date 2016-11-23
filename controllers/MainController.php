<?php
/**
 * User: srq
 * Date: 2016/11/23
 * Time: 9:29
 */

namespace app\controllers;
use app\models\Account;
use app\models\Remark;
use Yii;
use yii\helpers\Html;

class MainController extends MController {

    public function actionIndex() {
//        $a = Html::a('a',['/account/view','id'=>2]);
//        echo $a; exit;
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
        $stamp = strtotime($dates[2].'-'.$dates[1].'-'.$dates[0]);
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

}