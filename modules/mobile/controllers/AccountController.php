<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 11:24
 */

namespace app\modules\mobile\controllers;


class AccountController extends TController {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCreate() {
        return $this->render('create');
    }
}