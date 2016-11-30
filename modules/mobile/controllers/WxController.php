<?php
/**
 * User: srq
 * Date: 2016/11/30
 * Time: 11:22
 */

namespace app\modules\mobile\controllers;

use yii\web\Controller;

class WxController extends Controller {

    public function actionDispatch() {
        $g = Yii::$app->request->get();
        print_r($g);
        exit;
    }

    public function actionMenu() {
        '{
            "button":[
     {
         "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
          "name":"菜单",
           "sub_button":[
           {
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
                "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {
                "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
 }';
    }

    public function actionA() {
        $menu = ['button' =>
            [
                'type' => 'click',
                'name' => 'hehe',
                'key' => '',
            ],
            [
                "name" => "菜单",
                "sub_button" => [
                    "type" => "view",
                    "name" => "搜索",
                    "url" => "http://www.soso.com/"

                ]
            ]
        ];
        echo json_encode($menu);
    }
}