<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 14:23
 */

namespace app\modules\mobile\controllers;

class PlatformController extends TController {

    public function actionCreate() {
        return $this->render('create');
    }
}