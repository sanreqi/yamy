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

use yii\web\Controller;
use Yii;

class MController extends Controller {

    public $enableCsrfValidation = false;
    
    public function init() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
    }
}
