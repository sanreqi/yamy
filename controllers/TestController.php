<?php
/**
 * User: srq
 * Date: 2016/10/2
 * Time: 20:54
 */

namespace app\controllers;

use Yii;
use yii\di\Container;
use yii\web\Controller;

class TestController extends Controller {
    public function actionIndex() {
        $container = new \yii\di\Container;
        $a = Yii::$app->db;
        print_r($a);exit;

        $a = Yii::$app->getComponents();
        $container = new Container();
        print_r($container);exit;
        print_r($a);exit;

        // 直接以类名注册一个依赖，虽然这么做没什么意义。
        // $_definition['yii\db\Connection'] = 'yii\db\Connetcion'
        $r = $container->set('yii\db\Connection');

        // 注册一个接口，当一个类依赖于该接口时，定义中的类会自动被实例化，并供有依赖需要的类使用。
        // $_definition['yii\mail\MailInterface', 'yii\swiftmailer\Mailer']
        $container->set('yii\mail\MailInterface', 'yii\swiftmailer\Mailer');
//new \yii\db\Connection;
        // 注册一个别名，当调用$container->get('foo')时，可以得到一个 yii\db\Connection 实例。
        // $_definition['foo', 'yii\db\Connection']
        $container->set('foo', 'yii\db\Connection');
        $class = 'yii\db\Connection';
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();

//        print_r($constructor->getParameters());exit;
        foreach ($constructor->getParameters() as $param) {
            $c = $param->getClass();
            echo $c; exit;
        }
        print_r($r);exit;
    }

}