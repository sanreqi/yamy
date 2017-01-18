<?php
/**
 * User: srq
 * Date: 2017/1/18
 * Time: 10:24
 */

namespace app\controllers;

use app\models\Landmine;
use yii\db\Query;

class LandmineController extends MController {

    public function actionIndex() {
        $models = Landmine::find()->all();
        return $this->render('index', ['models' => $models]);
    }
}