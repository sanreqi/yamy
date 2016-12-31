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

    public $enableCsrfValidation = false;

    public function actionIndex() {
        $a = $this->render('index');
        echo $a;
        exit;
        return;
        $container = new \yii\di\Container;
        $a = Yii::$app->db;
        print_r($a);
        exit;

        $a = Yii::$app->getComponents();
        $container = new Container();
        print_r($container);
        exit;
        print_r($a);
        exit;

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
            echo $c;
            exit;
        }
        print_r($r);
        exit;
    }

    public function actionP2() {
        return 1;
    }

    public function actionP1() {
        header("Content-type: text/html; charset=utf-8");
        //        $url1 = 'http://yamy.local/site/login/';
        //        $data['LoginForm[username]'] = 'zhaji';
        //        $data['LoginForm[password]'] = 'ladyqq616';
        //        $postData = "LoginForm[username]=zhaji&LoginForm[password]=ladyqq616";
        //        $ch = curl_init();
        //        curl_setopt($ch, CURLOPT_URL, $url1);
        //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //        curl_setopt($ch, CURLOPT_HEADER, 0);
        //        curl_setopt($ch, CURLOPT_POST, 1);
        //        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //        $r = curl_exec($ch);
        //        echo $r; exit;

        //        curl_close($ch);
        //        curl_close($ch);
        //        http://yamy.local/instruction
        $url2 = 'http://yamy.local/account';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //        curl_setopt($ch, CURLOPT_POST, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
        exit;


        //echo $output;
        //        print_r(json_decode($output));
    }

    public function actionP3() {
        echo 'i am n'; exit;
        if (preg_match('%^[a-z][a-z0-9\\-_]*$%', 'a-___')) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
}