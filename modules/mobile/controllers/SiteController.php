<?php
/**
 * User: srq
 * Date: 2016/11/30
 * Time: 11:47
 */

namespace app\modules\mobile\controllers;

use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SiteController extends TController {

    public $layout = 'main';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
    public function actionLogout() {
        echo 1; exit;
        Yii::$app->user->logout();
        $this->redirect(['/mobile/site/login']);
    }

    public function actionT() {
        echo 1;
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