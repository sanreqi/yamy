<?php
/**
 * User: srq
 * Date: 2016/8/28
 * Time: 8:19
 */

namespace app\controllers;


use app\models\Account;
use app\models\BankAccount;
use yii\web\Controller;

class MigrationController extends Controller {

    public function actionDoPersonalInfo() {
        $bankAccount = BankAccount::find()->where(['is_deleted' => 0])->asArray()->all();
        if (!empty($bankAccount)) {
            foreach ($bankAccount as $v) {
                Account::updateAll(['bankaccount_id' => $v['id']], 'mobile="'.$v['reserved_phone'].'"');
            }
        }
    }
}