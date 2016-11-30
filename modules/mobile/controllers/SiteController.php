<?php
/**
 * User: srq
 * Date: 2016/11/30
 * Time: 11:47
 */

namespace app\modules\mobile\controllers;

use app\models\LoginForm;
use Yii;

class SiteController extends TController {

    public $layout = 'main';

    /**
     * 登录页面
     * @return string
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            $this->redirect(['/mobile/account/index']);
        }
        return $this->render('login');
    }

    /**
     * 登录提交
     * @return object
     */
    public function actionLoginAjax() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        $model = new LoginForm();
        $model->username = $data['username'];
        $model->password = $data['password'];
        if ($model->login()) {
            return $this->ajaxResponseSuccess();
        } else {
            return $this->ajaxResponseError("用户名或密码错误");
        }
    }
}