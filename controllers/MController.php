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
    
//    public function init() {
//        //待改进
//        if (Yii::$app->user->isGuest) {
//            header("Content-type: text/html; charset=utf-8");
//            echo '请先登录!' . '<a href="/site/login">点击这里</a>';
//            exit;
//        }
//    }

    /**
     * 检查用户权限并返回
     *
     * @param $permit 权限项名称，$isAjax是否ajax，决定返回内容
     * @return 直接向浏览器返回结果
     */
    protected function checkAccessAndResponse($permit, $params=[], $isAjax = true) {
        if (Yii::$app->user->can($permit, $params)) {
            return true;
        } else {
            if ($isAjax) {
                echo json_encode(['status' => -1, 'code' => 0, 'message' => 'ACCESS DENIED!']);
                exit();
            } else {
                Yii::$app->user->returnUrl = Yii::$app->request->url;
                $this->redirect('/login', true)->send();
                //echo 'ACCESS DENIED!';
                exit();
            }
        }
    }

    //ajax返回，默认status为1成功
    protected function ajaxResponse($data, $status) {
        //@formatter:off
        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'status' => $status,
                'data' => $data,
            ],
        ]);
        //@formatter:on
    }

    //ajax返回，失败
    protected function ajaxResponseError($errMsg, $errCode = 0) {
        //@formatter:off
        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'status' => -1,
                'code' => $errCode,
                'message' => $errMsg,
            ],
        ]);
        //@formatter:on
    }

    //ajax返回，成功
    protected function ajaxResponseSuccess($data = []) {
        return $this->ajaxResponse($data, 1);
    }

    //检查是否Ajax Request，不是则返回
    protected function checkIsAjaxRequestAndResponse() {
        if (!Yii::$app->request->isAjax) {
            echo "ACCESS DENIED! Only accept AJAX request";
            exit();
        } else {
            return true;
        }
    }

    /**
     * 从约定数组中得到主数据(data)
     *
     * @param 输入的ajax数据形如
     *      [
     *        type => xxx,
     *        data => [
     *          d1 => [...],
     *          d2 => [...],
     *          ...
     *       ]
     *     ]
     * @return $itemNames = ''：返回整个data内容
     *                    是数组：返回数组中指定key的data内容
     *                    是字符：返回该变量指定key的data内容
     *         如果找不到该数组，则返回空数组
     */
    protected function getAjaxData($itemNames = '') {
        $data = Yii::$app->request->post('data');

        //返回全部数据
        if (empty($itemNames)) {
            return $data;

            //返回指定的数据
        } else if (is_array($itemNames)) {

            $returnArray = [];
            foreach ($itemNames as $item) {
                $returnArray[$item] = isset($data[$item]) ? $data[$item] : [];
            }

            return $returnArray;
        } else {
            return isset($data[$itemNames]) ? $data[$itemNames] : [];
        }
    }

    //从约定数组中得到Type
    protected function getAjaxType() {
        $type = Yii::$app->request->post('type');
        return $type;
    }
}
