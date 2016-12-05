<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 14:23
 */

namespace app\modules\mobile\controllers;

use app\models\Platform;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PlatformController extends TController {

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
     * 创建平台页面
     * @return string
     */
    public function actionCreate() {
        return $this->render('create');
    }

    public function actionSaveAjax() {
        $this->checkIsAjaxRequestAndResponse();
        $data = $this->getAjaxData();
        $model = new Platform();
        $model->name = $data['name'];
        if ($model->save()) {
            return $this->ajaxResponseSuccess();
        } else {
            $error = '';
            $errors = $model->getErrors();
            foreach ($errors as $k => $v) {
                $error .= $v[0] . "\n";
            }
            return $this->ajaxResponseError($error);
        }
    }
}