<?php
namespace app\modules\mobile\libs;

use Yii;

/* * ***********************************************
 * 远程Http获得数据的库
 * 封装了和远程获得数据相关的接口
 * ***********************************************
 */

class RemoteHttp {

    //get
    public static function get($url, $queryArray = []) {
        $ch = curl_init();

        $queryString = '';
        if (!empty($queryArray)) {
            $queryString = "?" . http_build_query($queryArray);
        }

        curl_setopt($ch, CURLOPT_URL, $url . $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    //post表单数据
    public static function post($url, $postArray = []) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postArray);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    //post xml string
    public static function postXmlString($url, $xmlString) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml', 'Content-Length: ' . strlen($xmlString)]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    //post json数据
    public static function postJson($url, $jsonArray = []) {
        $ch = curl_init();

        $jsonString = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        if (false === $jsonString)
            return false;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonString)]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

}
