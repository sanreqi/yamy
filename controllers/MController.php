<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MController
 *
 * @author Administrator
 */
namespace app\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class MController extends Controller {

    public $enableCsrfValidation = false;
    
    public function init() {
        //待改进
        if (Yii::$app->user->isGuest) {
            header("Content-type: text/html; charset=utf-8");
            echo '请先登录!' . '<a href="/site/login">点击这里</a>';
//            echo 1; exit;
//            echo $this->renderPartial('/site/login');
//            echo $this->renderFile('../views/site/login.php');
//            return $this->redirect('/site/login');
//            echo 2;
            exit;
        }
    }
}
