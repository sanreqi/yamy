<?php
/**
 * User: srq
 * Date: 2016/11/28
 * Time: 14:23
 */

namespace app\modules\mobile\controllers;

use app\models\Platform;

class PlatformController extends TController {

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