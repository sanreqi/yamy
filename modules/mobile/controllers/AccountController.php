<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 11:24
 */

namespace app\modules\mobile\controllers;


use app\models\BankAccount;
use app\models\Platform;

class AccountController extends TController {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCreate() {
        $platformOptions = Platform::getOptions();
        $bankAccountOptions = BankAccount::getDisplayOptions();
        return $this->render('create', [
            'platformOptions' => $platformOptions,
            'bankAccountOptions' => $bankAccountOptions
        ]);
    }
}